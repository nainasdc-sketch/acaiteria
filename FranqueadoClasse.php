<?php
class Franqueado {
    private $idFranqueado;
    private $nome;
    private $sobrenome;
    private $email;
    private $fone;
    private $estado;
    private $cidade;

    public function __construct($idFranqueado = null, $nome = null, $sobrenome = null, $email = null, $fone = null, $estado = null, $cidade = null) {
        $this->idFranqueado = $idFranqueado;
        $this->nome = $nome;
        $this->sobrenome = $sobrenome;
        $this->email = $email;
        $this->fone = $fone;
        $this->estado = $estado;
        $this->cidade = $cidade;
    }

    public function getIdFranqueado() {
        return $this->idFranqueado;
    }

    public function setIdFranqueado($idFranqueado) {
        $this->idFranqueado = $idFranqueado;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getSobrenome() {
        return $this->sobrenome;
    }

    public function setSobrenome($sobrenome) {
        $this->sobrenome = $sobrenome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getFone() {
        return $this->fone;
    }

    public function setFone($fone) {
        $this->fone = $fone;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }
}
?>
