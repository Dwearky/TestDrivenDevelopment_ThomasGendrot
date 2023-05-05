<?php
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase {
    public function testSessionInitialization() {
        $_SESSION = array();
        require_once 'index.php';
        $this->assertTrue(isset($_SESSION["chatrooms"]));
        $this->assertTrue(isset($_SESSION["chatrooms"]["Salon 1"]));
        $this->assertTrue(isset($_SESSION["chatrooms"]["Salon 1"]["messages"]));
        $this->assertTrue(isset($_SESSION["chatrooms"]["Salon 2"]));
        $this->assertTrue(isset($_SESSION["chatrooms"]["Salon 2"]["messages"]));
        $this->assertTrue(isset($_SESSION["chatroom"]));
        $this->assertEquals($_SESSION["chatroom"], "Salon 1");
        $this->assertTrue(isset($_SESSION["users"]));
    }

    public function testPostMessageInvalidLength() {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST["message"] = "1";
        $_SESSION["user_id"] = "1";
        $_SESSION["users"]["1"]["Salon 1"] = time() - 86400;
        $_SESSION["chatroom"] = "Salon 1";
        $_SESSION["chatrooms"]["Salon 1"]["messages"] = array();
        require_once 'chat.php';
        $this->expectOutputString("Le message doit avoir entre 2 et 2048 caractères.");
    }

    public function testPostMessageInvalidTime() {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST["message"] = "Message valide";
        $_SESSION["user_id"] = "1";
        $_SESSION["users"]["1"]["Salon 1"] = time() - 86399;
        $_SESSION["chatroom"] = "Salon 1";
        $_SESSION["chatrooms"]["Salon 1"]["messages"] = array();
        require_once 'chat.php';
        $this->expectOutputString("Vous ne pouvez pas poster deux messages consécutifs. Veuillez attendre 1 secondes.");
    }

    public function testPostMessageValid() {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST["message"] = "Message valide";
        $_SESSION["user_id"] = "1";
        $_SESSION["users"]["1"]["Salon 1"] = time() - 86400;
        $_SESSION["chatroom"] = "Salon 1";
        $_SESSION["chatrooms"]["Salon 1"]["messages"] = array();
        require_once 'chat.php';
        $this->assertEquals($_SESSION["chatrooms"]["Salon 1"]["messages"][0], "1: Message valide");
        $this->assertEquals($_SESSION["users"]["1"]["Salon 1"], time());
    }

    public function testChangeChatroom() {
        $_GET["chatroom"] = "Salon 2";
        $_SESSION["chatroom"] = "Salon 1";
        require_once 'chat.php';
        $this->assertEquals($_SESSION["chatroom"], "Salon 2");
    }
}
?>
