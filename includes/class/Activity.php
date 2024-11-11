<?php


class Activity
{

    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function detect_os()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform  = "Unknown";

        $os_array = array(
            '/windows nt 10/i'      => 'Windows 10',
            '/windows nt 6.3/i'     => 'Windows 8.1',
            '/windows nt 6.2/i'     => 'Windows 8',
            '/windows nt 6.1/i'     => 'Windows 7',
            '/windows nt 6.0/i'     => 'Windows Vista',
            '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     => 'Windows XP',
            '/windows xp/i'         => 'Windows XP',
            '/windows nt 5.0/i'     => 'Windows 2000',
            '/windows me/i'         => 'Windows ME',
            '/win98/i'              => 'Windows 98',
            '/win95/i'              => 'Windows 95',
            '/win16/i'              => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i'        => 'Mac OS 9',
            '/linux/i'              => 'Linux',
            '/ubuntu/i'             => 'Ubuntu',
            '/iphone/i'             => 'iPhone',
            '/ipod/i'               => 'iPod',
            '/ipad/i'               => 'iPad',
            '/android/i'            => 'Android',
            '/blackberry/i'         => 'BlackBerry',
            '/webos/i'              => 'Mobile'
        );

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }

        return $os_platform;
    }
    public function add_article($word)
    {
        $vowels = array('a', 'e', 'i', 'o', 'u');

        if (in_array(strtolower(substr($word, 0, 1)), $vowels)) {
            return 'an ' . $word;
        } else {
            return 'a ' . $word;
        }
    }
    public function genRefId()
    {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '#';
        for ($i = 0; $i < 15; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }

    public function userLoggedIn($user, $type)
    {
        $device = $this->add_article($this->detect_os());
        $ref_id = $this->genRefId();
        $time = time();
        $time_created = date("d-m-Y h:ia", $time);
        $activity = "Logged in using $device";

        $sql = "INSERT INTO `activities_db`(`reference_id`, `activity`, `time_created`, `type`, `created_by`) VALUES (:ri, :ac, :tc, :ty, :cb)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':ri', $ref_id);
        $statement->bindParam(':ac', $activity);
        $statement->bindParam(':tc', $time_created);
        $statement->bindParam(':ty', $type);
        $statement->bindParam(':cb', $user);
        if ($statement->execute()) {
            return true;
        }
    }

    public function userCommission($user, $ref_id, $amount)
    {

        $commisssion = 0.10 * $amount;

        $activity = 'Outstanding commission of ' . $commisssion;
        $time = time();
        $type = 'commission';
        $time_created = date("d-m-Y h:ia", $time);
        $sql = "INSERT INTO `activities_db`(`reference_id`, `activity`, `time_created`, `type`, `created_by`) VALUES (:ri, :ac, :tc, :ty, :cb)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':ri', $ref_id);
        $statement->bindParam(':ac', $activity);
        $statement->bindParam(':tc', $time_created);
        $statement->bindParam(':ty', $type);
        $statement->bindParam(':cb', $user);
        if (
            $statement->execute()
        ) {
            return true;
        }
    }

    public function userDeposit($user, $ref_id, $method, $amount)
    {
        $activity = 'Made a funding request of ' . $amount . 'ETH via ' . ucfirst($method) . '';
        $time = time();
        $type = 'deposit';
        $time_created = date("d-m-Y h:ia", $time);
        $sql = "INSERT INTO `activities_db`(`reference_id`, `activity`, `time_created`, `type`, `created_by`) VALUES (:ri, :ac, :tc, :ty, :cb)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':ri', $ref_id);
        $statement->bindParam(':ac', $activity);
        $statement->bindParam(':tc', $time_created);
        $statement->bindParam(':ty', $type);
        $statement->bindParam(':cb', $user);
        if (
            $statement->execute()
        ) {
            return true;
        }
    }
    public function userWithdrawal($user, $ref_id, $method, $amount)
    {
        $activity = 'Made a withdrawal request of ' . $amount . 'ETH via ' . ucfirst($method) . '';
        $time = time();
        $type = 'withdraw';
        $time_created = date("d-m-Y h:ia", $time);
        $sql = "INSERT INTO `activities_db`(`reference_id`, `activity`, `time_created`, `type`, `created_by`) VALUES (:ri, :ac, :tc, :ty, :cb)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':ri', $ref_id);
        $statement->bindParam(':ac', $activity);
        $statement->bindParam(':tc', $time_created);
        $statement->bindParam(':ty', $type);
        $statement->bindParam(':cb', $user);
        if (
            $statement->execute()
        ) {
            return true;
        }
    }
    public function withdrawMintingToProfit($user, $ref_id, $amount)
    {
        $activity = 'Withdrawal of ' . $amount . 'ETH from Minting Balance to ETH (Arbitrum) Wallet';
        $time = time();
        $type = 'withdraw';
        $time_created = date("d-m-Y h:ia", $time);
        $sql = "INSERT INTO `activities_db`(`reference_id`, `activity`, `time_created`, `type`, `created_by`) VALUES (:ri, :ac, :tc, :ty, :cb)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':ri', $ref_id);
        $statement->bindParam(':ac', $activity);
        $statement->bindParam(':tc', $time_created);
        $statement->bindParam(':ty', $type);
        $statement->bindParam(':cb', $user);
        if (
            $statement->execute()
        ) {
            return true;
        }
    }
    public function purchaseArt($user, $ref_id, $title, $amount)
    {
        $activity = 'Purchased ' . $title . ' NFT for ' . $amount . 'ETH';
        $time = time();
        $time_created = date("d-m-Y h:ia", $time);
        $type = 'purchase';
        $sql = "INSERT INTO `activities_db`(`reference_id`, `activity`, `type`, `time_created`, `created_by`) VALUES (:ri, :ac, :ty, :tc, :cb)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':ri', $ref_id);
        $statement->bindParam(':ac', $activity);
        $statement->bindParam(':ty', $type);
        $statement->bindParam(':tc', $time_created);
        $statement->bindParam(':cb', $user);
        if (
            $statement->execute()
        ) {
            return true;
        }
    }
    public function salesArt($user, $ref_id, $title, $amount, $username)
    {
        $activity = 'Sold the ' . $title . ' NFT for ' . $amount . 'ETH to @' . $username . '';
        $time = time();
        $time_created = date("d-m-Y h:ia", $time);
        $type = 'sales';
        $sql = "INSERT INTO `activities_db`(`reference_id`, `activity`, `type`, `time_created`, `created_by`) VALUES (:ri, :ac, :ty, :tc, :cb)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':ri', $ref_id);
        $statement->bindParam(':ac', $activity);
        $statement->bindParam(':ty', $type);
        $statement->bindParam(':tc', $time_created);
        $statement->bindParam(':cb', $user);
        if (
            $statement->execute()
        ) {
            return true;
        }
    }
    public function updatedPass($user, $ref_id)
    {
        $activity = 'Updated your password';
        $time = time();
        $time_created = date("d-m-Y h:ia", $time);
        $type = 'login';
        $sql = "INSERT INTO `activities_db`(`reference_id`, `activity`, `type`, `time_created`, `created_by`) VALUES (:ri, :ac, :ty, :tc, :cb)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':ri', $ref_id);
        $statement->bindParam(':ac', $activity);
        $statement->bindParam(':ty', $type);
        $statement->bindParam(':tc', $time_created);
        $statement->bindParam(':cb', $user);
        if (
            $statement->execute()
        ) {
            return true;
        }
    }
    public function kycVerification($userId, $type)
    {
        $activity = 'Sent KYC verification request using ' . ucwords($type);
        $ref_id = $this->genRefId();
        $time = time();
        $time_created = date("d-m-Y h:ia", $time);
        $type = 'kyc';
        $sql = "INSERT INTO `activities_db`(`reference_id`, `activity`, `time_created`, `type`, `created_by`) VALUES (:ri, :ac, :tc, :ty, :cb)";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':cb', $userId);
        $statement->bindParam(':ac', $activity);
        $statement->bindParam(':ty', $type);
        $statement->bindParam(':tc', $time_created);
        $statement->bindParam(':ri', $ref_id);
        if ($statement->execute()) {
            return true;
        }
    }
}
