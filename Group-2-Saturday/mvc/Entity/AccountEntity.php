<?php 

class AccountEntity {
    public $id;
    public $email;
    public $phone;
    public $password;
    public $name;
    public $sex;
    public $avatar;
    public $birthday;
    public $role;
    public $block;

    // Constructor
    public function __construct($id, $email, $phone, $password, $name, $sex, $avatar, $birthday, $role, $block) {
        $this->id = $id;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->name = $name;
        $this->sex = $sex;
        $this->avatar = $avatar;
        $this->birthday = $birthday;
        $this->role = $role;
        $this->block = $block;
    }

    // Getter and Setter for id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter and Setter for email
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    // Getter and Setter for phone
    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    // Getter and Setter for password
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    // Getter and Setter for name
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    // Getter and Setter for sex
    public function getSex() {
        return $this->sex;
    }

    public function setSex($sex) {
        $this->sex = $sex;
    }

    // Getter and Setter for avatar
    public function getAvatar() {
        return $this->avatar;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    // Getter and Setter for birthday
    public function getBirthday() {
        return $this->birthday;
    }

    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    // Getter and Setter for role
    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    // Getter and Setter for block
    public function getBlock() {
        return $this->block;
    }

    public function setBlock($block) {
        $this->block = $block;
    }
}














?>