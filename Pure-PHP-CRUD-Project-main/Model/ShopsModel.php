<?php
require 'AbstractModel.php';

Class ShopsModel extends AbstractModel
{
    
    protected $db;

    public function __construct()
    {
        try {
            // Load database access configuration from the ini file
            $dbAccess = parse_ini_file('Config/database.ini', true);
            $dbAccess = $dbAccess['database'];

            
            $this->db = new mysqli($dbAccess['host'], $dbAccess['user'], $dbAccess['password'], $dbAccess['database']);

            // Check if the connection was successful
            if ($this->db->connect_error) {
                // If connection fails, throw an exception
                throw new Exception("Database connection failed: " . $this->db->connect_error);
            }
        } catch (Exception $e) {
            // Log the exception to a file
            $this->logException($e);

            // Rethrow the exception to propagate it further if needed
            throw $e;
        }
    }

    
    public function createShop($data)
    {
        // Validate the input data
        if (empty($data['shop-name']) || !is_string($data['shop-name'])) {
            throw new Exception("Invalid input for field: shop-name");
        }
        if (empty($data['shop-type']) || !is_string($data['shop-type'])) {
            throw new Exception("Invalid input for field: shop-type");
        }
        if (empty($data['shop-location']) || !is_string($data['shop-location'])) {
            throw new Exception("Invalid input for field: shop-location");
        }
        if (empty($data['shop-timesheet']) || !is_string($data['shop-timesheet'])) {
            throw new Exception("Invalid input for field: shop-timesheet");
        }

        try {
            // Extract shop data from the input array
            $shopName = $data['shop-name'];
            $shopType = $data['shop-type'];
            $shopLocation = $data['shop-location'];
            $shopTimesheet = $data['shop-timesheet'];

            // Prepare the SQL statement with placeholders for values
            $query = $this->db->prepare("INSERT INTO magasins (shop_name, shop_type, shop_location, shop_timesheet) VALUES (?, ?, ?, ?)");

            // Check if the query preparation was successful
            if (!$query) {
                throw new Exception("Failed to prepare the SQL statement");
            }

            // Bind the values to the prepared statement
            $query->bind_param("ssss", $shopName, $shopType, $shopLocation, $shopTimesheet);

            // Execute the prepared statement
            $executionResult = $query->execute();

            // Check if the execution was successful
            if (!$executionResult) {
                throw new Exception("Failed to execute the SQL statement");
            }

            // Note: Additional validation and error handling can be added as needed
        } catch (Exception $e) {
            // Log the exception to a file
            $this->logException($e);

            // Rethrow the exception to propagate it further if needed
            throw $e;
        }
    }


    public function getShopById($id)
    {
        // Validate the input ID
        if (!ctype_digit((string) $id)) {
            throw new InvalidArgumentException("Invalid input for ID");
        }

        try {
            // Prepare the SQL query to select the shop record(s)
            if ($id) {
                $query = $this->db->prepare("SELECT * FROM magasins WHERE deleted IS NULL AND id = ?");
                $query->bind_param("i", $id);
            } else {
                $query = $this->db->prepare("SELECT * FROM magasins WHERE deleted IS NULL");
            }

            // Check if the query preparation was successful
            if (!$query) {
                throw new RuntimeException("Failed to prepare the SQL statement: " . $this->db->error);
            }

            // Execute the query
            $executionResult = $query->execute();

            // Check if the execution was successful
            if (!$executionResult) {
                throw new RuntimeException("Failed to execute the SQL statement: " . $query->error);
            }

            // Get the result set
            $result = $query->get_result();

            // Fetch the result as an array of associative arrays
            $shops = $result->fetch_all(MYSQLI_ASSOC);

            // Return the shop data
            return $shops;
        } catch (Exception $e) {
            // Log the exception to a file
            $this->logException($e);

            // Rethrow the exception to propagate it further if needed
            throw $e;
        }
    }



    public function getShopByType($type)
    {
        // Validate the input type
        if (!is_string($type)) {
            throw new Exception("Invalid input for type");
        }

        try {
            if ($type == '* Reset') {
                return $this->getShopById('all');
            }

            // Prepare the SQL query to select shop records with the given type
            $query = $this->db->prepare("SELECT * FROM magasins WHERE deleted IS NULL AND shop_type = ?");
            $query->bind_param("s", $type);

            // Check if the query preparation was successful
            if (!$query) {
                throw new Exception("Failed to prepare the SQL statement");
            }

            // Execute the query
            $executionResult = $query->execute();

            // Check if the execution was successful
            if (!$executionResult) {
                throw new Exception("Failed to execute the SQL statement");
            }

            // Get the result set
            $result = $query->get_result();

            // Fetch the results as an array of associative arrays
            $shops = $result->fetch_all(MYSQLI_ASSOC);

            // Return the shop data
            return $shops;
        } catch (Exception $e) {
            // Log the exception to a file
            $this->logException($e);

            // Rethrow the exception to propagate it further if needed
            throw $e;
        }
    }


    public function getShopBySearch($searchTerm)
    {
        // Validate the input search term
        if (!is_string($searchTerm)) {
            throw new Exception("Invalid input for search term");
        }

        try {
            // Prepare the SQL query to select shop records with the given search term in any column
            $query = $this->db->prepare("SELECT * FROM magasins WHERE deleted IS NULL AND (shop_name LIKE ? OR shop_type LIKE ? OR shop_location LIKE ? OR shop_timesheet LIKE ?)");

            // Check if the query preparation was successful
            if (!$query) {
                throw new Exception("Failed to prepare the SQL statement");
            }

            // Add wildcard characters to the search term
            $searchTerm = '%' . $searchTerm . '%';

            // Bind the search term parameter to the query
            $query->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);

            // Execute the query
            $executionResult = $query->execute();

            // Check if the execution was successful
            if (!$executionResult) {
                throw new Exception("Failed to execute the SQL statement");
            }

            // Get the result set
            $result = $query->get_result();

            // Fetch the results as an array of associative arrays
            $shops = $result->fetch_all(MYSQLI_ASSOC);

            // Return the shop data
            return $shops;
        } catch (Exception $e) {
            // Log the exception to a file
            $this->logException($e);

            // Rethrow the exception to propagate it further if needed
            throw $e;
        }
    }


    public function updateShop($data)
    {
        try {
            // Extract the ID from the data
            $id = $data['id'];
            unset($data['id']);

            // Prepare arrays to hold the fields, types, and values for the update
            $fields = [];
            $types = "";
            $values = [];

            // Check which fields have changed and add them to the arrays
            foreach ($data as $key => $value) {
                $key = str_replace('-', '_', $key);
                $fields[] = "$key=?";
                $types .= "s";
                $values[] = $this->db->real_escape_string($value);
            }

            // Check if any fields have changed
            if (count($fields) > 0) {
                // Prepare the SQL statement for updating the shop record
                $querysql = "UPDATE magasins SET " . implode(", ", $fields) . " WHERE id = ?";
                $query = $this->db->prepare($querysql);

                // Check if the query preparation was successful
                if (!$query) {
                    throw new Exception("Failed to prepare the SQL statement: " . $this->db->error);
                }

                // Bind the parameters to the query
                array_push($values, $id);
                $query->bind_param($types . "i", ...$values);

                // Execute the query
                $executionResult = $query->execute();

                // Check if the execution was successful
                if (!$executionResult) {
                    throw new Exception("Failed to execute the SQL statement: " . $query->error);
                }

                // Check if the update was successful
                if ($query->affected_rows > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                // No fields have changed, so no update is necessary
                return false;
            }
        } catch (Exception $e) {
            // Log the exception to a file
            $this->logException($e);

            // Rethrow the exception to propagate it further if needed
            throw $e;
        }
    }
    

    public function deleteShop($id)
    {
        try {
            // Prepare the SQL statement for updating the deleted column of the shop record
            $query = $this->db->prepare("UPDATE magasins SET deleted = 1 WHERE id = ?");

            // Check if the query preparation was successful
            if (!$query) {
                throw new Exception("Failed to prepare the SQL statement: " . $this->db->error);
            }

            // Bind the ID parameter to the query
            $query->bind_param("i", $id);

            // Execute the query
            $executionResult = $query->execute();

            // Check if the execution was successful
            if (!$executionResult) {
                throw new Exception("Failed to execute the SQL statement: " . $query->error);
            }

            // Check if the update was successful
            if ($query->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Log the exception to a file
            $logMessage = "Exception: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine();
            $this->logException($logMessage);

            // Rethrow the exception to propagate it further if needed
            throw $e;
        }
    }

    private function logException($message)
    {
        $logFile = '../Logs/prod.log';
        $logMessage = date('[Y-m-d H:i:s]') . ' ' . $message . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

}
