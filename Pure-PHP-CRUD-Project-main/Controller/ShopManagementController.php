<?php

// Include the model
require 'Model/ShopsModel.php';

Class ShopManagementController
{
    public $shopsModel;

    public function __construct(){
        // initiate the model
        $this->shopsModel = new ShopsModel();
    }

    /**
     * This is the default function that will be launched when the page is intiated
    */
    public function index()
    {
        // Find the requested shop in the database
        $body = $this->shopsModel->getShopById(0);
        // Render the view
        include 'Views/index.php';
    }

    /**
     * This PHP function creates a new shop using provided data and returns a JSON response indicating
     * success or an error message if an exception or error occurs.
     */
    public function newShop()
    {
        // Set required headers for CORS and JSON response
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        
        try {

            // Create a new shop using the provided data ($_POST)
            $success = $this->shopsModel->createShop($_POST);
    
            // Check if the shop creation was successful
            if ($success) {
                // Return a success response
                http_response_code(200); // OK
                echo json_encode([true]); // Return a JSON response indicating success
            }
        } catch (Exception $e) {

            // Return an error response for error
            http_response_code(500);
            // Handle any exceptions or errors that occurred during the process
            echo json_encode(['error' => $e->getMessage()]); // Return an error message as JSON response
        }
    }

    /**
     * This PHP function updates a shop using the provided data and returns a JSON response indicating
     * success or an error message.
     */
    public function updateShop()
    {
        // Set required headers for CORS and JSON response
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: PATCH"); // Change the method to PATCH
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        try {

            $data = [];

            if(isset($_POST['id'])){
                $data['id'] = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
            }

            if(isset($_POST['shop-location'])){
                $data['shop-location'] = htmlspecialchars($_POST['shop-location'], ENT_QUOTES, 'UTF-8');
            }

            if(isset($_POST['shop-timesheet'])){
                $data['shop_timesheet'] = htmlspecialchars($_POST['shop-timesheet'], ENT_QUOTES, 'UTF-8');
            }

            if(isset($_POST['shop-type'])){
                $data['shop_type'] = htmlspecialchars($_POST['shop-type'], ENT_QUOTES, 'UTF-8');
            }

            if(isset($_POST['shop-name'])){
                $data['shop_name'] = htmlspecialchars($_POST['shop-name'], ENT_QUOTES, 'UTF-8');
            }

            // Update the shop using the provided data ($_POST in this case)
            $update = $this->shopsModel->updateShop($data);

            // Check if the shop update was successful
            if ($update) {
                // Set the response HTTP status code
                http_response_code(200); // OK

                echo json_encode(['success' => true]); // Return a JSON response indicating success
            } else {
                // Set the response HTTP status code
                http_response_code(500); // Internal Server Error

                echo json_encode(['error' => 'Failed to update shop']); // Return an error response
            }
        } catch (Exception $e) {
            // Set the response HTTP status code
            http_response_code(400); // Bad Request

            echo json_encode(['error' => $e->getMessage()]); // Return an error message as JSON response
        }
    }


    /**
     * This PHP function deletes a shop with a given ID and returns a JSON response indicating success
     * or an error message.
     */
    public function deleteShop()
    {
        // Set required headers for CORS and JSON response
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        try {
            // Validate and sanitize the input
            if (!isset($_POST['id'])) {
                throw new Exception("Missing required parameter: id");
            }

            $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');

            // Delete the shop with the given ID
            $delete = $this->shopsModel->deleteShop($id);

            // Check if the shop deletion was successful
            if ($delete) {
                // Set the response HTTP status code
                http_response_code(200); // OK

                echo json_encode(['success' => true]); // Return a JSON response indicating success
            } else {
                // Set the response HTTP status code
                http_response_code(500); // Internal Server Error

                echo json_encode(['error' => 'Failed to delete shop']); // Return an error response
            }
        } catch (Exception $e) {
            // Set the response HTTP status code
            http_response_code(400); // Bad Request

            echo json_encode(['error' => $e->getMessage()]); // Return an error message as JSON response
        }
    }



    /**
     * This PHP function retrieves shops from a database based on their type and returns the results as
     * a JSON response.
     */
    public function getShopsByType()
    {
        // Set required headers for CORS and JSON response
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        try {
            // Validate and sanitize the input
            if (!isset($_POST['type'])) {
                throw new Exception("Missing required parameter: Type");
            }
            $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');

            // Find the shops matching the requested type in the database
            $shops = $this->shopsModel->getShopByType($type);

            // Check if any shops were found
            if (!empty($shops)) {
                // Set the response HTTP status code
                http_response_code(200); // OK

                // Render the array as JSON response
                echo json_encode($shops);
            } else {
                // Set the response HTTP status code
                http_response_code(404); // Not Found

                echo json_encode(['error' => 'No shops found with the requested type']); // Return an error response
            }
        } catch (Exception $e) {
            // Set the response HTTP status code
            http_response_code(400); // Bad Request

            echo json_encode(['error' => $e->getMessage()]); // Return an error message as JSON response
        }
    }

    

    /**
     * This PHP function retrieves shops from a database based on a given name and returns the results
     * as a JSON response.
     */
    public function getShopsByName()
    {
        // Set required headers for CORS and JSON response
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        try {
            // Validate and sanitize the input
            if (!isset($_POST['name'])) {
                throw new Exception("Missing required parameter: Name");
            }
            $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');

            // Find the shops matching the requested name in the database
            $shops = $this->shopsModel->getShopBySearch($name);

            // Check if any shops were found
            if (!empty($shops)) {
                // Set the response HTTP status code
                http_response_code(200); // OK

                // Render the array as JSON response
                echo json_encode($shops);
            } else {
                // Set the response HTTP status code
                http_response_code(404); // Not Found

                echo json_encode(['error' => 'No shops found with the requested name']); // Return an error response
            }
        } catch (Exception $e) {
            // Set the response HTTP status code
            http_response_code(400); // Bad Request

            echo json_encode(['error' => $e->getMessage()]); // Return an error message as JSON response
        }
    }


}
