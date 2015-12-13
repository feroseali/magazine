<?php

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Ferose Ali
 */
class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    /* ------------- `users` table method ------------------ */

    /**
     * Creating new user
     * @param String $name User full name
     * @param String $email User login email id
     * @param String $password User login password
     */
    public function createUser($name, $email, $password) {
        require_once 'PassHash.php';
        $response = array();

        // First check if user already existed in db
        if (!$this->isUserExists($email)) {
            // Generating password hash
            $password_hash = PassHash::hash($password);

            // Generating API key
            $api_key = $this->generateApiKey();

            // insert query
            $stmt = $this->conn->prepare("INSERT INTO users(name, email, password_hash, api_key, status) values(?, ?, ?, ?, 1)");
            $stmt->bind_param("ssss", $name, $email, $password_hash, $api_key);

            $result = $stmt->execute();

            $stmt->close();

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        } else {
            // User with same email already existed in the db
            return USER_ALREADY_EXISTED;
        }

        return $response;
    }

    /**
     * Checking user login
     * @param String $email User login email id
     * @param String $password User login password
     * @return boolean User login status success/fail
     */
    public function checkLogin($email, $password) {
        // fetching user by email
        $stmt = $this->conn->prepare("SELECT password_hash FROM users WHERE email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->bind_result($password_hash);

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Found user with the email
            // Now verify the password

            $stmt->fetch();

            $stmt->close();

            if (PassHash::check_password($password_hash, $password)) {
                // User password is correct
                return TRUE;
            } else {
                // user password is incorrect
                return FALSE;
            }
        } else {
            $stmt->close();

            // user not existed with the email
            return FALSE;
        }
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    private function isUserExists($email) {
        $stmt = $this->conn->prepare("SELECT id from users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Fetching user by email
     * @param String $email User email id
     */
    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT name, email, api_key, status, created_at FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($name, $email, $api_key, $status, $created_at);
            $stmt->fetch();
            $user = array();
            $user["name"] = $name;
            $user["email"] = $email;
            $user["api_key"] = $api_key;
            $user["status"] = $status;
            $user["created_at"] = $created_at;
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }

    /**
     * Fetching user api key
     * @param String $user_id user id primary key in user table
     */
    public function getApiKeyById($user_id) {
        $stmt = $this->conn->prepare("SELECT api_key FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            // $api_key = $stmt->get_result()->fetch_assoc();
            // TODO
            $stmt->bind_result($api_key);
            $stmt->close();
            return $api_key;
        } else {
            return NULL;
        }
    }

    /**
     * Fetching user id by api key
     * @param String $api_key user api key
     */
    public function getUserId($api_key) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE api_key = ?");
        $stmt->bind_param("s", $api_key);
        if ($stmt->execute()) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            // TODO
            // $user_id = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user_id;
        } else {
            return NULL;
        }
    }

    /**
     * Validating user api key
     * If the api key is there in db, it is a valid key
     * @param String $api_key user api key
     * @return boolean
     */
    public function isValidApiKey($api_key) {
        $stmt = $this->conn->prepare("SELECT id from users WHERE api_key = ?");
        $stmt->bind_param("s", $api_key);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Generating random Unique MD5 String for user Api key
     */
    private function generateApiKey() {
        return md5(uniqid(rand(), true));
    }


    /* ------------- `categories` table method ------------- */

    /**
     * Creating new category
     * @param String $category_name Name of the category
     * @param String $category_description Description of the category
     * @param String $category_image Url of the category image
     */
    public function createCategory($cat_name, $cat_desc, $cat_img) {
	// First check if user already existed in db
        if (!$this->isCategoryExists($cat_name)) {
    		$stmt = $this->conn->prepare("INSERT INTO categories(category_name, category_description, category_image) VALUES(?,?,?)");
    		$stmt->bind_param("sss", $cat_name, $cat_desc, $cat_img);
    		$result = $stmt->execute();
    		$stmt->close();

    		if ($result) {
    		    // task created successfully
    		    $new_cat_id = $this->conn->insert_id;
    		    echo $new_cat_id;
    		    return 0;
    		} else {
    		    // task failed to create
    		    return 1;
    		}
    	}
    	else{
    		return 2;
    	}
    }

    /**
     * Checking for duplicate category by category name
     * @param String $cat_name category name to check in db
     * @return boolean
     */
    private function isCategoryExists($cat_name) {
        $stmt = $this->conn->prepare("SELECT * from categories WHERE category_name = ?");
        $stmt->bind_param("s", $cat_name);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Fetching all the categories
     */
  	public function getAllCategories() {
          $stmt = $this->conn->prepare("select * from categories");

          $stmt->execute();
          $stmt->store_result();

          $meta = $stmt->result_metadata();

          if ( $stmt -> num_rows > 0  && $meta != null) {

            while ($field = $meta->fetch_field()) {
              $params[] = &$row[$field->name];
            }
            call_user_func_array(array($stmt, 'bind_result'), $params);

            while ($stmt->fetch()) {
              $temp = array();
              foreach($row as $key => $val) {
                  $temp[$key] = $val;
              }
              $categories[] = $temp;
            }

            $meta->free();
            $stmt->close();
          }


          // $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
          return $categories;
      }

      
    /**
     * Fetching single category
     * @param integer $cat_id id of the category
     */
    public function getCategory($cat_id) {
        $stmt = $this->conn->prepare("SELECT c.id, c.category_name, c.category_description, c.category_image, c.created_at from categories c WHERE c.id = ?");
        $stmt->bind_param("i", $cat_id);
        if ($stmt->execute()) {
            $res = array();
            $stmt->bind_result($id, $category_name, $category_description, $category_image, $created_at);
            // TODO
            // $task = $stmt->get_result()->fetch_assoc();
            $stmt->fetch();
            $res["id"] = $id;
            $res["cat_name"] = $category_name;
            $res["cat_desc"] = $category_description;
            $res["cat_img"] = $category_image;
            $res["created_at"] = $created_at;
            $stmt->close();
            return $res;
        } else {
            return NULL;
        }
    }

    /**
     * Updating category
     * @param integer $cat_id id of the category
     * @param String $cat_name category name
     * @param String $cat_desc category description
     */
    public function updateCategories($cat_id, $cat_name, $cat_desc, $cat_img) {
        $stmt = $this->conn->prepare("UPDATE categories set category_name = ?, category_description = ?, category_image = ? WHERE id = ?");
        $stmt->bind_param('sssi', $cat_name, $cat_desc, $cat_img, $cat_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }


    /**
     * Deleting a category
     * @param String $cat_id id of the category to delete
     */
    public function deleteCategory($cat_id) {
        $stmt = $this->conn->prepare("DELETE t FROM categories t WHERE t.id = ?");
        $stmt->bind_param("i", $cat_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }


    /* ------------- `articles` table method ------------- */

    /**
     * Creating new article
     */
    public function createArticle($cat_id, $article_title, $article_image, $author_name, $date_published, $article_content) {
   // First check if article already existed in db
        if (!$this->isArticleExists($cat_id, $article_title)) {
        $stmt = $this->conn->prepare("INSERT INTO articles(cat_id, article_title, article_image, author_name, date_published, article_content) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param("isssss", $cat_id, $article_title, $article_image, $author_name, $date_published, $article_content);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // article created successfully
            $new_article_id = $this->conn->insert_id;
            echo $new_article_id;
            return 0;
        } else {
            // task failed to create
            return 1;
        }
      }
      else{
        return 2;
      }
    }

    /**
     * Checking for duplicate article by article_title in a category
     * @return boolean
     */
    private function isArticleExists($cat_id, $article_title) {
        $stmt = $this->conn->prepare("SELECT * from articles WHERE article_title = ? AND cat_id = ?");
        $stmt->bind_param("si", $article_title, $cat_id);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Fetching all the articles under a category
     */

    public function getArticlesByCategory($cat_id) {
          $stmt = $this->conn->prepare("SELECT a.id, a.cat_id, a.article_title, a.article_image, a.author_name, a.date_published, a.article_content from articles a WHERE a.cat_id = ?");
          $stmt->bind_param("i", $cat_id);
          $stmt->execute();
          $stmt->store_result();

          $meta = $stmt->result_metadata();

          if ( $stmt -> num_rows > 0  && $meta != null) {

            while ($field = $meta->fetch_field()) {
              $params[] = &$row[$field->name];
            }
            call_user_func_array(array($stmt, 'bind_result'), $params);

            while ($stmt->fetch()) {
              $temp = array();
              foreach($row as $key => $val) {
                  $temp[$key] = $val;
              }
              $articles[] = $temp;
            }

            $meta->free();
            $stmt->close();
          }


          return $articles;
      }


    /**
     * Fetching the details of an article
     */

    public function getArticle($article_id) {
        $stmt = $this->conn->prepare("SELECT a.id, a.cat_id, a.article_title, a.article_image, a.author_name, a.date_published, a.article_content from articles a WHERE a.id = ?");
        $stmt->bind_param("i", $article_id);
        if ($stmt->execute()) {
            $res = array();
            $stmt->bind_result($id, $cat_id, $article_title, $article_image, $author_name, $date_published, $article_content);
            $stmt->fetch();
            $res["id"] = $id;
            $res["cat_id"] = $cat_id;
            $res["article_title"] = $article_title;
            $res["article_image"] = $article_image;
            $res["author_name"] = $author_name;
            $res["date_published"] = $date_published;
            $res["article_content"] = $article_content;
            $stmt->close();
            return $res;
        } else {
            return NULL;
        }
    }

}

?>
