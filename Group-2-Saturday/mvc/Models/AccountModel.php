<?php 
//  require_once __DIR__ . '/../Core/dataAccount.php';
require_once __DIR__ . '/../Entity/AccountEntity.php';

class AccountModel extends DbConnection{
    private $conn;

    public function __construct() {
        $this->conn = new DbConnection();
    }

    public function getAllAccount() {
        $query = "SELECT * FROM Users";
        $result = $this->conn->getConnection()->query($query);

        $users = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                  $user = new AccountEntity(
                    $row['Id'],
                    $row['Email'],
                    $row['Phone'],
                    $row['Password'],
                    $row['Name'],
                    $row['Sex'],
                    $row['Avatar'],
                    $row['Birthday'],
                    $row['Role'],
                    $row['Block']
                  );
                  array_push($users, $user);
            }
        }
        return $users;
}
public function getIDbyEmail($email) {
    $query = "SELECT * FROM Users WHERE    Email='".$email . "'";
    $result = $this->conn->getConnection()->query($query);

    $users = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
              $user = new AccountEntity(
                $row['Id'],
                $row['Email'],
                $row['Phone'],
                $row['Password'],
                $row['Name'],
                $row['Sex'],
                $row['Avatar'],
                $row['Birthday'],
                $row['Role'],
                $row['Block']
              );
              array_push($users, $user);
        }
    }
    return $users[0]->Id;
}
public function findbyId($id){
    $query = "SELECT * FROM Users WHERE Id = '$id'";
    $result = $this->conn->getConnection()->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user = new AccountEntity(
            $row['Id'],
            $row['Email'],
            $row['Phone'],
            $row['Password'],
            $row['Name'],
            $row['Sex'],
            $row['Avatar'],
            $row['Birthday'],
            $row['Role'],
            $row['Block']
        );
        return $user;
    } else {
        // Trả về null nếu không tìm thấy nhân viên có id tương ứng
        return null;
    }
}
// public function addAccount($id, $email, $phone, $password, $name, $sex, $avatar, $birthday, $role, $block) {
//     // Mã hoá mật khẩu trước khi lưu vào cơ sở dữ liệu
//     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

//     $query = "INSERT INTO Users (Id, Email, Phone, Password, Name, Sex, Avatar, Birthday, Role, Block) 
//               VALUES ('$id', '$email', '$phone', '$hashedPassword', '$name', '$sex', '$avatar', '$birthday', '$role', '$block')";
    
//     // Thực thi truy vấn
//     $result = $this->conn->getConnection()->query($query);
    
//     if ($result === TRUE) {
//         return true;
//     } else {
//         return false;
//     }
// }
public function addAccount($id, $email, $phone, $password, $name, $sex, $avatar, $birthday, $role, $block) {
    // Mã hoá mật khẩu trước khi lưu vào cơ sở dữ liệu
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Kiểm tra và xử lý dữ liệu ảnh avatar
    if ($avatar === NULL) {
        $avatar = '';
    }

    $query = "INSERT INTO Users (Id, Email, Phone, Password, Name, Sex, Avatar, Birthday, Role, Block) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $this->conn->getConnection()->prepare($query);

    if ($stmt === false) {
        throw new Exception('Prepare failed: ' . $this->conn->getConnection()->error);
    }

    // Bind các tham số
    if (!$stmt->bind_param("ssssssssss", $id, $email, $phone, $hashedPassword, $name, $sex, $avatar, $birthday, $role, $block)) {
        throw new Exception('Binding parameters failed: ' . $stmt->error);
    }

    // Thực hiện truyền dữ liệu ảnh avatar nếu có
    if (!empty($avatar)) {
        $stmt->send_long_data(6, $avatar);
    }

    // Thực thi truy vấn
    if ($stmt->execute() === false) {
        throw new mysqli_sql_exception('Execute failed: ' . $stmt->error);
    }

    $stmt->close();
    return true;
}


public function checkEmailExists($email) {
    $query = "SELECT COUNT(*) as count FROM Users WHERE Email = ?";
    $stmt = $this->conn->getConnection()->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        if ($count > 0) {
            // Email đã tồn tại trong cơ sở dữ liệu
            return true;
        } else {
            // Email chưa tồn tại trong cơ sở dữ liệu
            return false;
        }
    } else {
        // Có lỗi xảy ra trong quá trình thực hiện truy vấn
        return false;
    }
}
public function updateAccountByEmail($id, $name, $phone, $sex, $birthday) {
    $query = "UPDATE Users SET Name=?, Phone=?, Sex=?, Birthday=? WHERE Id=?";
    $stmt = $this->conn->getConnection()->prepare($query);
    if ($stmt) {
        $stmt->bind_param("sssss", $name, $phone, $sex, $birthday, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true; // Cập nhật thành công
        } else {
            return false; // Không có bản ghi nào được cập nhật
        }
    } else {
        return false; // Lỗi trong quá trình thực hiện truy vấn
    }
}
public function deleteAccountById($id) {
    $query = "DELETE FROM Users WHERE Id = ?";
    $stmt = $this->conn->getConnection()->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true; 
        } else {
            return false; 
        }
    } else {
        return false; 
    }
}
public function CheckRole($username) {
    $sql = "SELECT Role FROM Users WHERE Email = ?";
    $stmt = $this->conn->getConnection()->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user['Role'];
    } else {
        echo "Error: " . $this->conn->getConnection()->error;
        die();
    }
}
public function setNameUser($username) {
    $sql = "SELECT Name FROM Users WHERE Email = ?";
    $stmt = $this->conn->getConnection()->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user['Name'];
    } else {
        echo "Error: " . $this->conn->getConnection()->error;
        die();
    }
}
public function getUserByEmail($email) {
    $query = "SELECT * FROM Users WHERE Email = ?";
    $stmt = $this->conn->getConnection()->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            echo "Error: " . $this->conn->getConnection()->error;
            die();
        }
        $userData = $result->fetch_assoc();
        $user = new AccountEntity(
            $userData['Id'],
            $userData['Email'],
            $userData['Phone'],
            $userData['Password'],
            $userData['Name'],
            $userData['Sex'],
            $userData['Avatar'],
            $userData['Birthday'],
            $userData['Role'],
            $userData['Block']
        );
        return $user;
    } else {
        echo "Error: " . $this->conn->getConnection()->error;
        die();
    }
}

// public function updateAvatar($username, $name_img) {
//     $updateQuery = "UPDATE Users SET Avatar = ? WHERE Email = ?";
//     $stmt = $this->conn->getConnection()->prepare($updateQuery);
//     if ($stmt) {
//         $stmt->bind_param("ss", $name_img, $username);
//         $stmt->execute();
//         if ($stmt->affected_rows > 0) {
//             return true;
//         } else {
//             return false;
//         }
//     } else {
//         echo "Error: " . $this->conn->getConnection()->error;
//         die();
//     }
// }
public function updateAvatar($email, $imageData) {
    $stmt = $this->conn->getConnection()->prepare("UPDATE Users SET Avatar = ? WHERE Email = ?");
    if ($stmt === false) {
        throw new Exception('Prepare failed: ' . $this->conn->getConnection()->error);
    }

    // Bind the parameters (blob for imageData, string for email)
    $null = NULL; // Đây là giá trị giả lập cho bind_param khi sử dụng dữ liệu nhị phân
    $stmt->bind_param("bs", $null, $email);

    // Gửi dữ liệu nhị phân vào câu lệnh đã chuẩn bị
    $stmt->send_long_data(0, $imageData);

    // Execute the query
    if ($stmt->execute() === false) {
        throw new mysqli_sql_exception('Execute failed: ' . $stmt->error);
    }

    $stmt->close();
    return true;
}


public function updateInfo($username, $phone_number, $name, $birthday, $gender) {
    $sql = "UPDATE Users 
            SET 
                Phone = ?,
                Name = ?,
                Birthday = ?,
                Sex = ?
            WHERE 
                Email = ?";
    
    $stmt = $this->conn->getConnection()->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssss", $phone_number, $name, $birthday, $gender, $username);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true; // Update successful
        } else {
            return false; // No records updated
        }
    } else {
        return false; // Error in query execution
    }
}
public function resetPassword($username, $newPassword) {
    // Kiểm tra xem username có tồn tại trong bảng Users không
    $query = "SELECT * FROM Users WHERE Email = ?";
    $stmt = $this->conn->getConnection()->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Mật khẩu mới được hash trước khi cập nhật vào cơ sở dữ liệu
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Cập nhật mật khẩu mới vào cơ sở dữ liệu
            $updateQuery = "UPDATE Users SET Password = ? WHERE Email = ?";
            $updateStmt = $this->conn->getConnection()->prepare($updateQuery);
            if ($updateStmt) {
                $updateStmt->bind_param("ss", $hashedPassword, $username);
                $updateStmt->execute();
                if ($updateStmt->affected_rows > 0) {
                    return true; // Trả về true nếu cập nhật thành công
                } else {
                    return false; // Trả về false nếu có lỗi khi cập nhật
                }
            } else {
                return false; // Lỗi trong quá trình chuẩn bị truy vấn cập nhật
            }
        } else {
            return false; // Trả về false nếu username không tồn tại trong cơ sở dữ liệu
        }
    } else {
        return false; // Lỗi trong quá trình thực hiện truy vấn
    }
}

public function getOldPass($username) {
    $query = "SELECT Password FROM Users WHERE Email = ?";
    $stmt = $this->conn->getConnection()->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            return $userData['Password'];
        } else {
            return null; // Old password not found
        }
    } else {
        return null; // Error in query execution
    }
}

public function updatePass($username, $newPass) {
    // Hash the new password
    $hashedPassword = password_hash($newPass, PASSWORD_DEFAULT);
    
    // Prepare the SQL statement
    $query = "UPDATE Users SET Password = ? WHERE Email = ?";
    $stmt = $this->conn->getConnection()->prepare($query);
    
    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("ss", $hashedPassword, $username);
        $stmt->execute();
        
        // Check if the password was updated successfully
        if ($stmt->affected_rows > 0) {
            return true; // Password update successful
        } else {
            return false; // Password update failed
        }
    } else {
        return false; // Error in query execution
    }
}
public function CreateAccount($id, $email, $phone, $password, $name, $sex, $avatar, $birthday, $role, $block) {
    // Mã hóa mật khẩu bằng bcrypt
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO Users (Id, Email, Phone, Password, Name, Sex, Avatar, Birthday, Role, Block) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->getConnection()->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ssssssssss", $id, $email, $phone, $hashedPassword, $name, $sex, $avatar, $birthday, $role, $block);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true; // Tạo tài khoản thành công
        } else {
            return false; // Tạo tài khoản thất bại
        }
    } else {
        return false; // Lỗi trong quá trình thực hiện truy vấn
    }
}

public function CreateClient($id) {
    $point = 0;
    $query = "INSERT INTO Client (IdClient, Point) VALUES (?, ?)";
    $stmt = $this->conn->getConnection()->prepare($query);
    
    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("si", $id, $point);
        $stmt->execute();
        
        // Check if the client was created successfully
        if ($stmt->affected_rows > 0) {
            return true; // Client creation successful
        } else {
            return false; // Client creation failed
        }
    } else {
        return false; // Error in query execution
    }
}
public function generateUniqueID() {
    do {
        // Tạo một UUID mới
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        $query = "SELECT Id FROM Users WHERE Id = ?";
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Nếu ID chưa tồn tại, trả về giá trị ID
        if ($result->num_rows == 0) {
            return $id;
        }
        // Nếu ID đã tồn tại, lặp lại quá trình để tạo ID mới
    } while (true);
}

public function findBlockByEmail($email) {
    $query = "SELECT Block FROM Users WHERE Email = ?";
    $stmt = $this->conn->getConnection()->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) { 
            $userData = $result->fetch_assoc();
            return $userData['Block']; // Trả về giá trị của trường Block
        } else {
            return null; // Không tìm thấy email trong cơ sở dữ liệu
        }
    } else {
        return null; // Lỗi trong quá trình thực hiện truy vấn
    }
}


public function updateBlockStatus($id, $block) {
    // Xây dựng câu truy vấn SQL để cập nhật trường Block
    // public function updateAvatar($username, $name_img) {
        $updateQuery = "UPDATE Users SET Block = ? WHERE Id = ?";
        $stmt = $this->conn->getConnection()->prepare($updateQuery);
        if ($stmt) {
            $stmt->bind_param("ss", $block, $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            echo "Error: " . $this->conn->getConnection()->error;
            die();
        }
    }
    public function findIdByEmail($email) {
        $query = "SELECT Id FROM Users WHERE Email = ?";
        $stmt = $this->conn->getConnection()->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) { 
                $userData = $result->fetch_assoc();
                return $userData['Id']; // Trả về ID tương ứng với địa chỉ email
            } else {
                return null; // Không tìm thấy email trong cơ sở dữ liệu
            }
        } else {
            return null; // Lỗi trong quá trình thực hiện truy vấn
        }
    }

    public function searchAccounts($searchTerm) {
        // Sanitize the input to prevent SQL injection
        $searchTerm = $this->conn->getConnection()->real_escape_string($searchTerm);
        // Add wildcards for partial matching
        $searchTerm = "%" . $searchTerm . "%";
    
        $query = "SELECT * FROM Users WHERE Email LIKE ? OR Name LIKE ? OR Sex LIKE ? OR Phone LIKE ? OR Birthday LIKE ?";
        $stmt = $this->conn->getConnection()->prepare($query);
    
        // Bind parameters
        $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    
        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();
    
        $users = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new AccountEntity(
                    $row['Id'],
                    $row['Email'],
                    $row['Phone'],
                    $row['Password'],
                    $row['Name'],
                    $row['Sex'],
                    $row['Avatar'],
                    $row['Birthday'],
                    $row['Role'],
                    $row['Block']
                );
                array_push($users, $user);
            }
        }
    
        // Close the statement
        $stmt->close();
    
        return $users;
    }
    
}        
    

?>
