<?php

/**
 * Created by PhpStorm.
 * User: Mouadh
 * Date: 01/04/2017
 * Time: 23:41
 */
namespace UserBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUpload
{

    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    
    public function upload(UploadedFile $file)
    {
        $file_name = md5(uniqid()).'.'.$file->guessExtension() ;

        $file->move($this->targetDir, $file_name);

        return $file_name;
    }

    public function deleteImage($path_image)
    {
        if ($path_image != null) {
            $filename = "upload/" . $path_image;
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
    }



    public  function getImages($inputName = 'slim') {

        $values = $this->getPostData($inputName);

        // test for errors
        if ($values === false) {
            return false;
        }

        // determine if contains multiple input values, if is singular, put in array
        $data = array();
        if (!is_array($values)) {
            $values = array($values);
        }

        // handle all posted fields
        foreach ($values as $value) {
            $inputValue = $this->parseInput($value);
            if ($inputValue) {
                array_push($data, $inputValue);
            }
        }

        // return the data collected from the fields
        return $data;

    }


    // $value should be in JSON format
    private  function parseInput($value) {

        // if no json received, exit, don't handle empty input values.
        if (empty($value)) {return null;}

        // If magic quotes enabled
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }

        // The data is posted as a JSON String so to be used it needs to be deserialized first
        $data = json_decode($value);

        // shortcut
        $input = null;
        $actions = null;
        $output = null;
        $meta = null;

        if (isset ($data->input)) {

            $inputData = null;
            if (isset($data->input->image)) {
                $inputData = $this->getBase64Data($data->input->image);
            }
            else if (isset($data->input->field)) {
                $filename = $_FILES[$data->input->field]['tmp_name'];
                if ($filename) {
                    $inputData = file_get_contents($filename);
                }
            }

            $input = array(
                'data' => $inputData,
                'name' => $data->input->name,
                'type' => $data->input->type,
                'size' => $data->input->size,
                'width' => $data->input->width,
                'height' => $data->input->height,
            );

        }

        if (isset($data->output)) {

            $outputDate = null;
            if (isset($data->output->image)) {
                $outputData = $this->getBase64Data($data->output->image);
            }
            else if (isset ($data->output->field)) {
                $filename = $_FILES[$data->output->field]['tmp_name'];
                if ($filename) {
                    $outputData = file_get_contents($filename);
                }
            }

            $output = array(
                'data' => $outputData,
                'name' => $data->output->name,
                'type' => $data->output->type,
                'width' => $data->output->width,
                'height' => $data->output->height
            );
        }

        if (isset($data->actions)) {
            $actions = array(
                'crop' => $data->actions->crop ? array(
                    'x' => $data->actions->crop->x,
                    'y' => $data->actions->crop->y,
                    'width' => $data->actions->crop->width,
                    'height' => $data->actions->crop->height,
                    'type' => $data->actions->crop->type
                ) : null,
                'size' => $data->actions->size ? array(
                    'width' => $data->actions->size->width,
                    'height' => $data->actions->size->height
                ) : null,
                'rotation' => $data->actions->rotation,
                'filters' => $data->actions->filters ? array(
                    'sharpen' => $data->actions->filters->sharpen
                ) : null
            );
        }

        if (isset($data->meta)) {
            $meta = $data->meta;
        }

        // We've sanitized the base64data and will now return the clean file object
        return array(
            'input' => $input,
            'output' => $output,
            'actions' => $actions,
            'meta' => $meta
        );
    }


    // $path should have trailing slash
    public  function saveFile($data, $name, $path = 'upload/', $uid = true) {

        // Add trailing slash if omitted
        if (substr($path, -1) !== '/') {
            $path .= '/';
        }

        // Test if directory already exists
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }

        // Sanitize characters in file name
        $name = $this->sanitizeFileName($name);

        // Let's put a unique id in front of the filename so we don't accidentally overwrite other files
        if ($uid) {
            $name = uniqid() . '_' . $name;
        }

        // Add name to path, we need the full path including the name to save the file
        $path = $path . $name;

        // store the file
        $this->save($data, $path);

        // return the files new name and location
        return array(
            'name' => $name,
            'path' => $path
        );
    }



    /**
     * Get data from remote URL
     * @param $url
     * @return string
     */
    public  function fetchURL($url, $maxFileSize) {
        if (!ini_get('allow_url_fopen')) {
            return null;
        }
        $content = null;
        try {
            $content = @file_get_contents($url, false, null, 0, $maxFileSize);
        } catch(Exception $e) {
            return false;
        }
        return $content;
    }


    public  function outputJSON($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public  function sanitizeFileName($str) {
        // Basic clean up
        $str = preg_replace('([^\w\s\d\-_~,;\[\]\(\).])', '', $str);
        // Remove any runs of periods
        $str = preg_replace('([\.]{2,})', '', $str);
        return $str;
    }
    /**
     * Gets the posted data from the POST or FILES object. If was using Slim to upload it will be in POST (as posted with hidden field) if not enhanced with Slim it'll be in FILES.
     * @param $inputName
     * @return array|bool
     */
    private  function getPostData($inputName) {

        $values = array();

        if (isset($_POST[$inputName])) {
            $values = $_POST[$inputName];
        }
        else if (isset($_FILES[$inputName])) {
            // Slim was not used to upload this file
            return false;
        }

        return $values;
    }



    /**
     * Saves the data to a given location
     * @param $data
     * @param $path
     * @return bool
     */
    private  function save($data, $path) {
        if (!file_put_contents($path, $data)) {
            return false;
        }
        return true;
    }
    /**
     * Strips the "data:image..." part of the base64 data string so PHP can save the string as a file
     * @param $data
     * @return string
     */
    /**
     * Strips the "data:image..." part of the base64 data string so PHP can save the string as a file
     * @param $data
     * @return string
     */
    private  function getBase64Data($data) {
        return base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
    }
    public function getIdvideo($url){
        //initialisation des variables
        $host = '';
        $id = '';
        $formated_url = '';

        //On détermine où est hebergée la vidéo (youtube, dailymotion, vimeo) et on extrait les données nécessaires au formatage du lien shadowbox
        $parse = parse_url($url);

        switch ($parse['host']) {
            case 'youtu.be':
                $host = 'youtube';
                $id = substr($parse['path'], 1);
                break;
            case 'www.youtube.com':
                $host = 'youtube';
                $parse2 = parse_str($parse['query'], $data);
                $id = $data['v'];
                break;
            case 'vimeo.com':
                $host = 'vimeo';
                $id = substr($parse['path'], 1);
                break;
            case 'www.dailymotion.com':
                $host = 'dailymotion';
                $id = substr($parse['path'], 7);
                break;

            default:
                break;
        }

        //On formate le lien selon l'hébergeur
        switch ($host) {
            case 'youtube':
                $formated_url = "https://www.youtube.com/watch?v=".$id."?autoplay=1";

                break;
            case 'vimeo':
                $formated_url =  "https://player.vimeo.com/video/".$id."?color=ffffff&title=0&byline=0&portrait=0?autoPlay=1" ;

                break;
            case 'dailymotion':
                $formated_url =  "http://www.dailymotion.com/embed/video/".$id."?autoPlay=1";

                break;

            default:
                break;
        }

        $coordonnses_video[0] = $host;
        $coordonnses_video[1] = $formated_url;
        return $coordonnses_video;
    }



}