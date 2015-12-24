<?php

require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '20 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'slim_session',
    'secret' => 'CHANGE_ME',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));
// User id from db - Global Variable
$user_id = NULL;

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

    // Verifying Authorization Header
    if (isset($headers['Authorization'])) {
        $db = new DbHandler();

        // get the api key
        $api_key = $headers['Authorization'];
        // validating api key
        if (!$db->isValidApiKey($api_key)) {
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Api key";
            echoRespnse(401, $response);
            $app->stop();
        } else {
            global $user_id;
            // get user primary key id
            $user_id = $db->getUserId($api_key);
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * ----------- METHODS WITHOUT AUTHENTICATION ---------------------------------
 */
/**
 * User Registration
 * url - /register
 * method - POST
 * params - name, email, password
 */
$app->post('/register', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('name', 'email', 'password'));

            $response = array();

            // reading post params
            $name = $app->request->post('name');
            $email = $app->request->post('email');
            $password = $app->request->post('password');

            // validating email address
            validateEmail($email);

            $db = new DbHandler();
            $res = $db->createUser($name, $email, $password);

            if ($res == USER_CREATED_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "You are successfully registered";
            } else if ($res == USER_CREATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registereing";
            } else if ($res == USER_ALREADY_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this email already existed";
            }
            // echo json response
            echoRespnse(201, $response);
        });

/**
 * User Login
 * url - /login
 * method - POST
 * params - email, password
 */
$app->post('/login', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('email', 'password'));

            // reading post params
            $email = $app->request()->post('email');
            $password = $app->request()->post('password');
            $response = array();

            $db = new DbHandler();
            // check for correct email and password
            if ($db->checkLogin($email, $password)) {
                // get the user by email
                $user = $db->getUserByEmail($email);

                if ($user != NULL) {
                    $response["error"] = false;
                    $response['name'] = $user['name'];
                    $response['email'] = $user['email'];
                    $response['apiKey'] = $user['api_key'];
                    $response['createdAt'] = $user['created_at'];
                    $_SESSION['user'] = $user['email'];
                    $_SESSION['token'] = $user['api_key'];
                    global $user_id;
                    $user_id = $user['api_key'];
                    // $app->redirect('../home', array('title' => 'Home'));
                } else {
                    // unknown error occurred
                    $response['error'] = true;
                    $response['message'] = "An error occurred. Please try again";
                }
            } else {
                // user credentials are wrong
                $response['error'] = true;
                $response['message'] = 'Login failed. Incorrect credentials';
            }

            echoRespnse(200, $response);
        });

        /**
         * User Login
         * url - /login
         * method - POST
         * params - email, password
         */
        // $app->post('/logout', function() use ($app) {
        //     unset($_SESSION['user'];
        //
        //     $app->redirect('../', array('title' => 'Login'));
        // });

/**
 * Create Category
 * url - /categories
 * method - POST
 * params - category_name, category_description
 */
$app->post('/categories', 'authenticate', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('category_name', 'category_description', 'category_image'));

            // reading post params
            $cat_name = $app->request()->post('category_name');
            $cat_desc = $app->request()->post('category_description');
            $cat_img = $app->request()->post('category_image');
            $response = array();

            $db = new DbHandler();
            $res = $db->createCategory($cat_name, $cat_desc, $cat_img);
            // $img_file = $_FILES["category_image"]["name"];

            // $folderName = "media";
            // if (!file_exists($folderName)) {
            //   $oldmask = umask(0);
            //   mkdir($folderName, 0777);
            //   umask($oldmask);
            // }
            // Generate a unique name for the image
            // to prevent overwriting the existing image
            // $filePath = $folderName. rand(10000, 990000). '_'. time().'.'.$ext;

            // if ( move_uploaded_file( $_FILES["category_image"]["tmp_name"], $filePath)) {
            //     $res = $db->createCategory($cat_name, $cat_desc, $filePath);
            //   } else {
            //     $res = 1;
            //   }


            if ($res == 0) {
                $response["error"] = false;
                $response["message"] = "Category successfully created";
            } else if ($res == 1) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while creating category";
            } else if ($res == 2) {
                $response["error"] = true;
                $response["message"] = "Sorry, this category name already existed";
            }
            echoRespnse(200, $response);
        });

/**
 * Create Category
 * url - /categories
 * method - PUT
 * params - category_name, category_description
 */
$app->put('/categories/:id', 'authenticate', function($cat_id) use ($app) {
            // check for required params
            verifyRequiredParams(array('category_name'));
            // reading post params
            $cat_name = $app->request()->put('category_name');
            $cat_desc = $app->request()->put('category_description');
            $cat_img = $app->request()->put('category_image');
            $response = array();

            $db = new DbHandler();
            $result = $db->updateCategories($cat_id, $cat_name, $cat_desc, $cat_img);

            if ($result == 0) {
                $response["error"] = false;
                $response["message"] = "Category successfully updated";
            } else if ($result == 1) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while updating category";
            } else if ($result == 2) {
                $response["error"] = true;
                $response["message"] = "Sorry, this category name already existed";
            }
            echoRespnse(200, $response);
        });


/**
 * Listing all categories
 * method GET
 * url /categories
 */
$app->get('/categories', function() {
            $response = array();
            $db = new DbHandler();

            // fetching all categories
            $result = $db->getAllCategories();

            $response["error"] = false;
            $response["categories"] = array();
            foreach($result as $row) {
                $tmp = array();
                $tmp["id"] = $row["id"];
                $tmp["category_name"] = $row["category_name"];
                $tmp["category_description"] = $row["category_description"];
                $tmp["category_image"] = $row["category_image"];
                $tmp["created_at"] = $row["created_at"];
                array_push($response["categories"], $tmp);
            }
            echoRespnse(200, $response);
        });

/**
 * Listing single category
 * method GET
 * url /categories/:id
 * Will return 404 if the task doesn't belongs to user
 */
$app->get('/categories/:id', function($cat_id) {
            $response = array();
            $db = new DbHandler();

            // fetch task
            $result = $db->getCategory($cat_id);

            if ($result != NULL) {
                $response["error"] = false;
                $response["id"] = $result["id"];
                $response["category_name"] = $result["cat_name"];
                $response["category_description"] = $result["cat_desc"];
                $response["category_image"] = $result["cat_img"];
                $response["createdAt"] = $result["created_at"];
                echoRespnse(200, $response);
            } else {
                $response["error"] = true;
                $response["message"] = "The requested resource doesn't exists";
                echoRespnse(404, $response);
            }
        });

/**
 * Deleting category. Admin can delete only the category.
 * method DELETE
 * url /tasks
 */
$app->delete('/categories/:id', 'authenticate', function($cat_id) use($app) {
            $db = new DbHandler();
            $response = array();
            $result = $db->deleteCategory($cat_id);
            if ($result) {
                // category deleted successfully
                $response["error"] = false;
                $response["message"] = "Category deleted succesfully";
            } else {
                // category failed to delete
                $response["error"] = true;
                $response["message"] = "Category failed to delete. Please try again!";
            }
            echoRespnse(200, $response);
        });


/**
 * Create Articles
 * url - /articles
 * method - POST
 * params - category_name, category_description
 */
$app->post('/categories/:id/articles', 'authenticate', function($cat_id) use ($app) {
            // check for required params
            verifyRequiredParams(array('article_title', 'article_image', 'author_name', 'date_published', 'article_content'));

            // reading post params
            $article_title = $app->request()->post('article_title');
            $article_image = $app->request()->post('article_image');
            $author_name = $app->request()->post('author_name');
            $date_published = $app->request()->post('date_published');
            $article_content = $app->request()->post('article_content');

            $response = array();

            $db = new DbHandler();
            $res = $db->createArticle($cat_id, $article_title, $article_image, $author_name, $date_published, $article_content);

            if ($res == 0) {
                $response["error"] = false;
                $response["message"] = "Article successfully created";
            } else if ($res == 1) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while creating this article";
            } else if ($res == 2) {
                $response["error"] = true;
                $response["message"] = "Sorry, this article already existed";
            }
            echoRespnse(200, $response);
        });

/**
 * Listing all articles under a category
 * method GET
 * url /categories
 */
$app->get('/categories/:id/articles', function($cat_id) use ($app){
            $response = array();
            $db = new DbHandler();

            // fetching all articles under category
            $result = $db->getArticlesByCategory($cat_id);

            $response["error"] = false;
            $response["category"] = array();
            $response["articles"] = array();
            $category_result = $db->getCategory($cat_id);
            if ($category_result != NULL) {
                $res = array();
                $res["cat_id"] = $category_result["id"];
                $res["category_name"] = $category_result["cat_name"];
                $res["category_description"] = $category_result["cat_desc"];
                $res["category_image"] = $category_result["cat_img"];
                $res["createdAt"] = $category_result["created_at"];
                array_push($response["category"], $res);
                foreach($result as $row) {
                    $tmp = array();
                    $tmp["id"] = $row["id"];
                    $tmp["article_title"] = $row["article_title"];
                    $tmp["article_image"] = $row["article_image"];
                    $tmp["author_name"] = $row["author_name"];
                    $tmp["date_published"] = $row["date_published"];
                    $tmp["article_content"] = $row["article_content"];
                    array_push($response["articles"], $tmp);
                }
            }
            echoRespnse(200, $response);
        });

/**
 * Listing single article under a category
 * method GET
 * url /categories/{category id}/articles/{article id}
 */
$app->get('/articles/:id', function($article_id) use ($app){
            $response = array();
            $db = new DbHandler();

            // fetching all articles under category
            $result = $db->getArticle($article_id);
            $reslt = $db->getCategory($result["cat_id"]);
            $response["error"] = false;
            if ($result != NULL) {
                $response["error"] = false;
                $response["id"] = $result["id"];
                $response["cat_id"] = $result["cat_id"];
                $response["cat_name"] = $reslt["cat_name"];
                $response["article_title"] = $result["article_title"];
                $response["article_image"] = $result["article_image"];
                $response["author_name"] = $result["author_name"];
                $response["date_published"] = $result["date_published"];
                $response["article_content"] = $result["article_content"];
                echoRespnse(200, $response);
            } else {
                $response["error"] = true;
                $response["message"] = "The requested resource doesn't exists";
                echoRespnse(404, $response);
            }
          });

        /**
         * Listing all articles
         * method GET
         * url /articles
         */

        $app->get('/articles', function(){
                    $response = array();
                    $db = new DbHandler();

                    // fetching all articles
                    $result = $db->getAllArticles();
                    $response["error"] = false;
                    $response["articles"] = array();
                    foreach($result as $row) {
                        $tmp = array();
                        $tmp["id"] = $row["id"];
                        $tmp["cat_id"] = $row["cat_id"];
                        $tmp["article_title"] = $row["article_title"];
                        $tmp["article_image"] = $row["article_image"];
                        $tmp["author_name"] = $row["author_name"];
                        $tmp["date_published"] = $row["date_published"];
                        $tmp["article_content"] = $row["article_content"];
                        array_push($response["articles"], $tmp);
                    }
                    echoRespnse(200, $response);
        });

        /**
         * Deleting articl. Admin can delete only the article.
         * method DELETE
         * url /categories/:id
         */
        $app->delete('/articles/:id', 'authenticate', function($art_id) use($app) {
                    $db = new DbHandler();
                    $response = array();
                    $result = $db->deleteArticle($art_id);
                    if ($result) {
                        // Article deleted successfully
                        $response["error"] = false;
                        $response["message"] = "Article deleted succesfully";
                    } else {
                        // Article failed to delete
                        $response["error"] = true;
                        $response["message"] = "Article failed to delete. Please try again!";
                    }
                    echoRespnse(200, $response);
                });


/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->run();
?>
