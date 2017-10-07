<?php

namespace Casa;

/**
* Responsável pelos usuários do sistema.
* @package  Casa
* @author   Leonardo Alves <leoallvez@hotmail.com>
* @access   public
*/
class AdmSistema extends Usuario 
{
    public function __construct(array $attributes = array(), $password = null) {
        parent::__construct($attributes, $password, UsuarioNivel::ADM_SISTEMA);
    }

    /**
     * @return Colletion de Usuario.
     */
    public static function list() 
    {
        $adm = self::where('nivel_id','=', UsuarioNivel::ADM_SISTEMA);
        return $adm->orderBy('name')->paginate(10);
    }

    public static function fetch($request, $nivel = UsuarioNivel::ADM_SISTEMA) 
    {
        return parent::fetch($request, $nivel);
    }
}
