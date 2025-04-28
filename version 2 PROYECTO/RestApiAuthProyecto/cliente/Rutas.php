<?php
class Rutas {

    protected $urlBase = "http://localhost/RestApiAuthProyecto"; // URL BASE DEL FRONT -> http://localhost:80, http://localhost:81 ,http://localhost:90


    /**
     * retorna la url base de tu proyecto / aplicación
     */
    public function getUrlBase() {
        return $this->urlBase;
    }

    public function getUrlFront() {
        return $this->urlBase."/cliente"; // URL / DIRECTORIO DE FRONT
    }

    public function getUrlApi()
    {
        return $this->urlBase.'/servidor/api'; // URL / DIRECTORIO DE TU API
    }
    // AQUI VAN RUTAS API 

    public function getRegisterApiUrl()
    {
        return $this->getUrlApi() .'/UsuariosAPI.php?action=register';

        
    }
    public function getloginApiUrl()
    {
        return $this->getUrlApi() .'/UsuariosAPI.php?action=login';
    }

    public function getlogoutApiUrl(string $token): string
    {
        return $this->getUrlApi() .'/UsuariosAPI.php?action=logout&token="'.$token;
    }

     // Rutas de API para gestión de usuarios (administrador)
     
    public function getUsuariosApiUrl() {
        return $this->getUrlApi() . "/UsuariosAPI.php";
    }

    public function getListarClientesUsuariosUrl() {
        return $this->getUsuariosApiUrl() . "?action=listarClientes";
    }

    public function getVerClienteUsuarioUrl() {
        return $this->getUsuariosApiUrl() . "?action=verCliente&id=1";
    }

    public function getActualizarUsuarioUrl() {
        return $this->getUsuariosApiUrl() . "?action=actualizarCliente&id=1";
    }

    public function getEliminarUsuarioUrl() {
        return $this->getUsuariosApiUrl() . "?action=eliminarCliente&id=1";
    }
 //contratistas

 public function getListarContratistasUsuariosUrl() {
    return $this->getUsuariosApiUrl() . "?action=listarContratistas";
}

public function getVerContratistaUsuarioUrl() {
    return $this->getUsuariosApiUrl() . "?action=verContratista&id=1";
}

public function getActualizarContratistaUrl() {
    return $this->getUsuariosApiUrl() . "?action=actualizarContratista&id=1";
}

public function getEliminarContratistaUrl() {
    return $this->getUsuariosApiUrl() . "?action=eliminarContratista&id=1";
}



}

?>
