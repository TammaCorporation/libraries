<?php 
    /**
     * *********************************************************************************************************
     * @_forProject: General Use | Developed At: TAMMA CORPORATION
     * @_purpose: (resize, compress and save images) 
     * @_version Release: package_one
     * @_created Date: 1/29/2020
     * @_author(s):
     *   1) Mr. Michael kaiva Nimley. (Hercules d Newbie)
     *      @contact Phone: (+231) 777-007-009
     *      @contact Mail: michaelkaivanimley.com@gmail.com, mnimley6@gmail.com, mnimley@tammacorp.com
     *   -------------------------------------------------------------------------------------------------------
     *   2) Fullname of engineer. (Code Name)
     *      @contact Phone: (+231) 000-000-000
     *      @contact Mail: -----@tammacorp.com
     * *********************************************************************************************************
     */

    class ResizeAndCompressImage
    {
        public $new_width;
        public $new_height;
        public $finalImage;
        public $imageType;
        public $imageCreatedFromFile;
        public $requestedDimension;

        // NOTE: supported image sizes
        public $supportedDimension = array(
            "thumbnail_xs"   =>  array( "width" => 125, "height" => 125 ),
            "thumbnail_sm"   =>  array( "width" => 200, "height" => 200 ),
            "thumbnail_m"    =>  array( "width" => 400, "height" => 400 ),
            "thumbnail_lg"   =>  array( "width" => 600, "height" => 600 )
        );

        function __construct(Object $options) {
            $this->options = $options;
        }

        public function getResizedImage() {
            return $this->dimensionValidator();
        }

        public function dimensionValidator() {
            switch ($this->options->dimension) {
                case 'thumbnail_xs':
                case 'thumbnail_sm':
                case 'thumbnail_m':
                case 'thumbnail_lg':
                    $this->new_width  = $this->supportedDimension[$this->options->dimension]['width'];
                    $this->new_height = $this->supportedDimension[$this->options->dimension]['height'];
                    return $this->processImageByType();
                    break;
                default:
                    if (is_array($this->options->dimension)) {
                        if ( 
                            !empty($this->options->dimension['width']) && 
                            !empty($this->options->dimension['height']) 
                        ) {
                            $this->new_width  = $this->options->dimension['width'];
                            $this->new_height = $this->options->dimension['height'];
                            return $this->processImageByType();
                        } else {
                            return [
                                "status" => false,
                                "body" => [
                                    "message" => "Custom dimensions must be array and contain a specified width and height",
                                    "result"  => null
                                ]
                            ];                    
                        }
                    } else {
                        return [
                            "status" => false,
                            "body" => [
                                "message" => "The supplied dimension is unsupported. Supported dimensions: thumbnail_xs, thumbnail_sm, thumbnail_m, thumbnail_lg or custom",
                                "result"  => null
                            ]
                        ];
                    }
                break;
            }
        }

        public function processImageByType() {
            // 
            if ( is_array($this->options->file_path) ) {
                $this->imageType =  $this->options->file_path['type'];
            } else {
                $this->imageType = pathinfo($this->options->file_path)['extension'];
            }

            // 
            switch ( $this->imageType ) {
                case 'jpg':
                case 'jpeg':
                    $this->imageCreatedFromFile  =  imagecreatefromjpeg( $this->options->file_path );
                    break;
                case 'png':
                    $this->imageCreatedFromFile  =  imagecreatefrompng( $this->options->file_path );
                    break;
                case 'gif':
                    $this->imageCreatedFromFile  =  imagecreatefromgif( $this->options->file_path );
                    break;
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/png':
                case 'image/gif':
                    $this->imageType              =   explode("/", $this->imageType)[1];
                    $img                          =   file_get_contents( $this->options->file_path['tmp_name'] );
                    $this->imageCreatedFromFile   =   imagecreatefromstring( $img );
                    break;
                default:
                    return [
                        "status" => false,
                        "body"   => [
                            "message" => $this->imageType." is unsupported. Supported file types: jpeg, jpg, png, gif",
                            "result"  => null
                        ]
                    ];
                    break;
            }
            // NOTE: start file compression
            return $this->compressAndResizeImage();
        }

        public function compressAndResizeImage() {   
            // get image orginal width and height
            $img_original_width   =  imagesx($this->imageCreatedFromFile);
            $img_original_height  =  imagesy($this->imageCreatedFromFile);
            $makeImage            =  ($this->imageType == "jpg") ? 'imagejpeg' : 'image'.$this->imageType;

            // Maintain aspect ratio
            $source_image   =  $this->imageCreatedFromFile;
            $source_imagex  =  imagesx($this->imageCreatedFromFile);
            $source_imagey  =  imagesy($this->imageCreatedFromFile);
            // 
            $dest_imagex = $this->new_width;
            $dest_imagey = $this->new_height;
            $dest_image = imagecreatetruecolor($dest_imagex, $dest_imagey);

            // specify quality of output
            if ( $this->options->quality == "low" ) {
                imagecopyresized($dest_image, $source_image, 0, 0, 0, 0, $dest_imagex, $dest_imagey, $source_imagex, $source_imagey);
            } 
            else if ( $this->options->quality == "high" ) {
                imagecopyresampled($dest_image, $source_image, 0, 0, 0, 0, $dest_imagex, $dest_imagey, $source_imagex, $source_imagey);
            }

            // scale image down to requested dimension
            $newImg = imagescale ( $dest_image, $this->new_width, $this->new_height,  IMG_BILINEAR_FIXED );
            // $newImg = imagescale ( $this->imageCreatedFromFile, $this->new_width, $this->new_height,  IMG_BILINEAR_FIXED );


            // NOTE: to be able to process image and place it back into $_FILES
            // $tmpfname = tempnam("/tmp", "UL_IMAGE"); // create location and name of new file in tmp storage
            // $img = file_get_contents($url); // get saved file
            // file_put_contents($tmpfname, $img); // place saved file into newly created tmp storage


            // 
            if ( empty($this->options->save) ) {
                return [
                    "status" => true,
                    "body" => [
                        "message" => "your image was resized and compressed. for useage instructions see ['implementation_guide']",
                        "result"  => $newImg,
                        "implementation_guide" => [
                            "save" => '  $img_thumb = $resizedImage->getResizedImage();
                            header("Content-Type: image/jpeg"); 
                            imagejpeg($img_thumb, "file_path"."image_name.extension")',
                            "display_in_browser" => '  $img_thumb = $resizedImage->getResizedImage();
                            header("Content-Type: image/jpeg"); 
                            imagejpeg($img_thumb)',
                        ]
                    ]
                ]; 
            } 
            elseif ( $this->options->save == "base64" ) {
                ob_start (); 
                    $makeImage( $newImg );
                    $image_data = ob_get_contents(); 
                ob_end_clean (); 
                return [
                    "status" => true,
                    "body" => [
                        "message" => "your image was resized, compressed and converted to base64",
                        "result"  => "data:image/x-icon;base64,".base64_encode($image_data),
                        "implementation_guide" => [
                            "display_in_browser" =>  htmlspecialchars( '$img_thumb = $resizedImage->getResizedImage(); '. 
                            "<img src=". '<?php echo $img_thumb["body"]["result"]; ?> alt="">')
                        ]
                    ]
                ];
            }
            else {
                $result = $makeImage( $newImg, $this->options->save.".".$this->imageType ); 
                // 
                if ( $result == 1 ) {
                    return [
                        "status" => true,
                        "body" => [
                            "message" => "your image was resized, compressed and saved",
                            "result"  => null
                        ]
                    ];
                } else {
                    return [
                        "status" => false,
                        "body" => [
                            "message" => "image resize and compression failed",
                            "result"  => null
                        ]
                    ];
                }
            }
        }
    }
    




if ($_FILES['image']) {
    //  STEP 1) instantiate compression class
    $resizedImage = new ResizeAndCompressImage((object)[
        "file_path"   =>  $_FILES['image'],  // path to image absolute/relative
        "dimension"   =>  "thumbnail_xs",  // options: [thumbnail_xs], [thumbnail_sm], [thumbnail_m], [thumbnail_lg] or custom: array("width"=>240,"height"=>70)
        "save"        =>  "base64", // options: [base64], [file_path and neme without extension]. if left empty, resized and compressed file resource id will be returned
        "quality"     =>  "low" // options: [high]/[low]
    ]);
    // STEP 2) make call to [getResizedImage] method
    $img_thumb = $resizedImage->getResizedImage();

    // STEP 3) OPTIONAL - dump result to explore usage instructions
    print "<pre>";
        print_r($img_thumb);
    print "</pre>";
    
    // QUICK PREVIEW EXAMPLE
    ?>
        <center style="margin-top:10%">
            <img src="<?php echo $img_thumb['body']['result']; ?>" style="display:block; margin-bottom:3%;">
            <img src="<?php echo $img_thumb['body']['result']; ?>" style="display:block; border-radius:50%">
        </center>
    <?php
} else {
    //  STEP 1) instantiate compression class
    $resizedImage = new ResizeAndCompressImage((object)[
        "file_path"   =>  "http://tammacorp.com/schoolmass/SETUP/photos/Miss%20Liberia.jpg",  // path to image absolute/relative
        "dimension"   =>  "thumbnail_xs",  // options: [thumbnail_xs], [thumbnail_sm], [thumbnail_m], [thumbnail_lg] or custom: array("width"=>240,"height"=>70)
        "save"        =>  "base64", // options: [base64], [file_path and neme without extension]. if left empty, resized and compressed file resource id will be returned
        "quality"     =>  "low" // options: [high]/[low]
    ]);
    // STEP 2) make call to [getResizedImage] method
    $img_thumb = $resizedImage->getResizedImage();

    // STEP 3) OPTIONAL - dump result to explore usage instructions
    print "<pre>";
        print_r($img_thumb);
    print "</pre>";
    
    // QUICK PREVIEW EXAMPLE
    ?>
        <center style="margin-top:10%">
            <img src="<?php echo $img_thumb['body']['result']; ?>" style="display:block; margin-bottom:3%;">
            <img src="<?php echo $img_thumb['body']['result']; ?>" style="display:block; border-radius:50%">
        </center>
    <?php    
}



?>
