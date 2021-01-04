<?php

namespace PaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackBundle\Entity\Payments;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\Details;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Amount;
use PayPal\Api\ItemList;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaymentController extends Controller
{
    
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     */
    public function prepareAction($idBook)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('session')->set('idBook', $idBook);
        $book = $em->getRepository('BackBundle:Collectionmedia')->find($idBook);
        
        $entity = $em->getRepository('BackBundle:Payments')->findBy(array(
            "book" => $book,
            "user" => $this->getUser()
        ));
        
        if ($book->getPriceBook() != null && $book->getPriceBook() > 0 && $entity == null) {

            $information = $em->getRepository('BackBundle:Information')->findOneBy(array(), array(), 1);
            $tax         = 0;
            $delivery    = 0;
            
            /** calcul price Tax  */
            if ($information->getTax() != null && $information->getTax() != 0)
                $tax = number_format($this->getVatPrice($information->getTax(), $book->getPriceBook() ), 2, '.', ' ');
        
            if ($information->getDelivery() != null && $information->getDelivery() != 0)
                $delivery = number_format($information->getDelivery(), 2, '.', ' ');
            
            $apiContext = new ApiContext(new OAuthTokenCredential($this->getParameter('id_paypal'), $this->getParameter('id_paypal_Secret')));
            
            
            
            $payer = new Payer();
            $payer->setPaymentMethod("paypal");
            // ### Itemized information
            // (Optional) Lets you specify item wise
            // information
            $item1 = new Item();
            $item1->setName( $book->getTitle())->setCurrency('EUR')->setQuantity(1)->setSku(rand()) // Similar to `item_number` in Classic API
                ->setPrice($book->getPriceBook() );
            
            $itemList = new ItemList();
            $itemList->setItems(array(
                $item1
            ));
            // ### Additional payment details
            // Use this optional field to set additional
            // payment information such as tax, shipping
            // charges etc. 
            $details = new Details();
            $details->setShipping($delivery)->setTax($tax)->setSubtotal($book->getPriceBook() );
            // ### Amount
            // Lets you specify a payment amount.
            // You can also specify additional details
            // such as shipping, tax.
            $total = $tax + $book->getPriceBook()  + $delivery;
            
            $amount = new Amount();
            $amount->setCurrency("EUR")->setTotal($total)->setDetails($details);
            // ### Transaction
            // A transaction defines the contract of a
            // payment - what is the payment for and who
            // is fulfilling it.
            $transaction = new Transaction();
            $transaction->setAmount($amount)->setItemList($itemList)->setDescription("Payment description")->setInvoiceNumber(uniqid());
            // ### Redirect urls
            // Set the urls that the buyer must be redirected to after
            // payment approval/ cancellation.
            $redirectUrls = new RedirectUrls();
            $url          = $this->getBaseUrl() . $this->generateUrl('payment_pay');
            $redirectUrls->setReturnUrl("$url?success=true")->setCancelUrl("$url?success=false");
            ### Payment
            // A Payment Resource; create one using
            // the above types and intent set to 'sale'
            $payment = new Payment();
            $payment->setIntent("sale")->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions(array(
                $transaction
            ));
            $request = clone $payment;
            try {
                $payment->create($apiContext);
                
            }
            catch (\PayPal\Exception\PayPalConnectionException $ex) {
                
                var_dump($ex); // Prints the Error Code
                exit();
                
            }
            
          echo  $approvalUrl = $payment->getApprovalLink();
           header('Location: ' . $approvalUrl);
           exit(); 
          //  echo ("Created Payment Using PayPal. Please visit the URL to Approve." . "Payment" . "<a href='$approvalUrl' >$approvalUrl</a>");
          //  var_dump($payment);
          //  exit();
        }  
       if ($book->getPathBook() != null ){
        $approvalUrl = $this->getParameter('upload_Url'). $book->getPathBook() ;
        header('Location: ' . $approvalUrl);
       exit();
        
    }else{ return $this->redirectToRoute('collection', array(
        'fullName' => $book->getMember()->getUsername () ,
        'activeTab' => "videos",
            'id' => $book->getMember()->getId()
        ));
    }
    }
    
    
    
    /**
     *
     */
    public function payAction()
    {
        
        $em          = $this->getDoctrine()->getManager();
        $book        = $em->getRepository('BackBundle:Collectionmedia')->find($this->get('session')->get('idBook'));
        $information = $em->getRepository('BackBundle:Information')->findOneBy(array(), array(), 1);
        $tax         = 0;
        $delivery    = 0;
        $translator  = $this->get('translator');
        
        if ($information->getTax() != null && $information->getTax() != 0)
            $tax = number_format($this->getVatPrice($information->getTax(), $book->getPriceBook() ), 2, '.', ' ');
        
        if ($information->getDelivery() != null && $information->getDelivery() != 0)
            $delivery = number_format($information->getDelivery(), 2, '.', ' ');
        
        $apiContext = new ApiContext(new OAuthTokenCredential($this->getParameter('id_paypal'), $this->getParameter('id_paypal_Secret')));
        // ### Approval Status
        if (isset($_GET['success']) && $_GET['success'] == 'true') {
            
            $paymentId = $_GET['paymentId'];
            $payment   = Payment::get($paymentId, $apiContext);
            // ### Payment Execute
            
            $execution = new PaymentExecution();
            $execution->setPayerId($_GET['PayerID']);
            
            $transaction = new Transaction();
            $amount      = new Amount();
            $details     = new Details();
            $details->setShipping($delivery)->setTax($tax)->setSubtotal($book->getPriceBook() );
            $total = $tax + $book->getPriceBook()  + $delivery;
             $amount->setCurrency('EUR');
            $amount->setTotal($total);
            $amount->setDetails($details);
            $transaction->setAmount($amount);
            // Add the above transaction object inside our Execution object.
            $execution->addTransaction($transaction);
            try {
                $result = $payment->execute($execution, $apiContext);
                //  echo("Executed Payment". "Payment". $payment->getId(). $execution. $result);
                try {
                    $payment = Payment::get($paymentId, $apiContext);
                }
                catch (\PayPal\Exception\PayPalConnectionException $ex) {
                    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
                    echo ("Get Payment" . "Payment" . null . null . $ex);
                    exit(1);
                }
            }
            catch (\PayPal\Exception\PayPalConnectionException $ex) {
                // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
              //  echo ("Executed Payment" . "Payment" . null . null . $ex);
                var_dump($ex);
                exit(1);
            }
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            //  echo("Get Payment". "Payment". $payment->getId(). null. $payment);
            
            
            $entity = new Payments();
            $entity->setIdPayment($payment->getId());
            $entity->setUser($this->getUser());
            $entity->setBook($book);
            
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('collectionVideossuccess', $translator->trans('alert.payement_sucees'));
            
        } else {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            //   echo ("User Cancelled the Approval". null);
            $this->get('session')->getFlashBag()->add('collectionVideossuccess', $translator->trans('alert.annuler_payement'));
            
        }
        
        return $this->redirectToRoute('collection', array(
            'activeTab' => "videos",
            'fullName' => $book->getMember()->getUsername ()  ,
            'id' => $book->getMember()->getId()
        ));
        
        
    }
              
    
    /**
     * @return string
     */
    function getBaseUrl()
    {
        
        // output: localhost
        $hostName = $_SERVER['HTTP_HOST'];
        
        // output: http://
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
        
        // return: http://localhost/myproject/
        return $protocol . '://' . $hostName;
    }
    
    public function getVatPrice($rate, $prix)
    {
        return round($prix * $rate) / 100;
    }
}
