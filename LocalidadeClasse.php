<?php
class Localidade {
    private $idLocalidade;
    private $estado;
    private $permissao;

    public function __construct($idLocalidade = null, $estado = null, $permissao = null) {
        $this->idLocalidade = $idLocalidade;
        $this->estado = $estado;
        $this->permissao = $permissao;
    }

    public function getIdLocalidade() {
        return $this->idLocalidade;
    }

    public function setIdLocalidade($idLocalidade) {
        $this->idLocalidade = $idLocalidade;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getPermissao() {
        return $this->permissao;
    }

    public function setPermissao($permissao) {
        $this->permissao = $permissao;
    }
}
?>
