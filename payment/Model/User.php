<?php

class User
{
    private string $first_name;
    private string $last_name;
    private string $email;
    private float $amount;
    private string $username;
    private string $phone_number;
    private string $tx_ref;
    private string $currency;
    private string $callback_url;
    private string $return_url;
    private string $customization;

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function create()
    {
        if (!isset($_POST['submit']) && !empty($_POST)) {
            $this->first_name = $_POST['first_name'];
            $this->last_name = $_POST['last_name'];
            $this->email = $_POST['email'];
            $this->callback_url = $_POST['callback_url'];
            $this->username = $_POST['username'];
            $this->amount = $_POST['amount'];
            $this->currency = $_POST['currency'];
            $this->return_url = $_POST['return_url'];
            $this->phone_number = $this->getPhoneNumber();
            $arr = $this->generateTextRef();

            $id = intval($arr[1]);
            $id += 1;

            $this->tx_ref = "chkela-" . $arr[0] . "-" . $id;
        }
    }

    public function validate_inputs(): array
    {
        $errors = [];

        if (empty($_POST['first_name'])) {
            $errors[] = 'first name field must not be empty';
        }

        if (empty($_POST['last_name'])) {
            $errors[] = 'last name field must not be empty';
        }

        if (empty($_POST['email'])) {
            $errors[] = 'email field must not be empty';
        }

        if (empty($_POST['username'])) {
            $errors[] = 'username field must not be empty';
        }

        if (empty($_POST['amount'])) {
            $errors[] = 'amount field must not be empty';
        }

        if (empty($_POST['currency'])) {
            $errors[] = 'currency type field must not be empty';
        }

        if (empty($_POST['return_url'])) {
            $errors[] = 'return url field must not be empty';
        }

        return $errors;
    }

    public function getTextRef(): string
    {
        return $this->tx_ref;
    }

    public function getAsKeyValue(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'callback_url' => $this->callback_url,
            'return_url' => $this->return_url,
            'phone_number' => $this->phone_number,
            'tx_ref' => $this->tx_ref
        ];
    }

    public function getfirst_name(): string
    {
        return $this->first_name;
    }

    public function getlast_name(): string
    {
        return $this->last_name;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getPhoneNumber(): string
    {
        $sql = 'SELECT Phone FROM Student
                WHERE userName = :username';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":username", $this->username, PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data['Phone'];
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getReturnUrl(): string
    {
        return $this->return_url;
    }

    public function getCustomization(): string
    {
        return $this->customization;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCallbackUrl(): string
    {
        return $this->callback_url;
    }

    public function generateTextRef(): array
    {
        $sql = "SELECT transaction_ref, transaction_id FROM
            transactions LEFT JOIN Student ON transactions.user_id = Student.ID 
            WHERE Student.Phone = :phone_number;";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":phone_number", $this->phone_number, PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return array($data['transaction_ref'], $data['transaction_id']);
    }
}
