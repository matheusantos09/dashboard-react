<?php

namespace App\Repositories;

use Cache;


class Ftps
{
    private $login;
    private $pass;


    public function __construct()
    {
        $this->login = env('KINGHOST_LOGIN');
        $this->pass = env('KINGHOST_PASS');
    }


    public function all()
    {
        $ftpsAll = unserialize( Cache::get('ftpsAll') );

        if(empty($ftpsAll)){
            require_once(__DIR__.'/../../vendor/kinghost/api-php/Dominio.php');
            $dominio = new \Dominio($this->login, $this->pass);

            $listaDominiosNaoAlterar = array(
                'netzee.com.br',
                'dashboard.netzee.com.br',
                'guilhermehenrique.com.br',
                'bastosja.com.br',
                'camocruz.sp.gov.br',
                'iacri.sp.gov.br',
                'linoforte.com.br',
                'lojaimperiofitness.com.br',
                'lojarace.com.br',
                'ludora.com.br',
                'lustrespracasa.com.br',
                'mallustore.com.br',
                'rinopolis.sp.gov.br',
                'santopolisdoaguapei.sp.gov.br',
                'trinys.net.br',
                'jfguedes.com',
                'maxieduca.com.br',
                'marquezim.com.br',
                'softjoias.com.br',
            );

            //OBTER TODOS OS DOMINIOS DA REVENDA
            $dominiosLista = $dominio->getDominios();

            //vejo se o status da consulta esta ok
            if(is_array($dominiosLista) && $dominiosLista['status'] == "ok") {
                //if(! in_array($dominiosLista['status']))
                $i=0;
                foreach($dominiosLista['body'] as $d) {

                    //se nao tiver na lista de dominios permitidos, morre
                    if(in_array($d['dominio'], $listaDominiosNaoAlterar)) continue;

                    $ftpsAll[$i]['dominio']= $d['dominio'];
                    $ftpsAll[$i]['id'] = $d['id'];
                    $i++;
                }

                Cache::put('ftpsAll', serialize($ftpsAll), 1440);
            }else{
                $ftpsAll = $dominiosLista;
            }

            return $ftpsAll;
        }

        return $ftpsAll;
    }

    public function changePass($site, $id)
    {
        require_once(__DIR__.'/../../vendor/kinghost/api-php/Ftp.php');
        $ftp = new \Ftp($this->login, $this->pass);

        //gerando a senha
        $senhaNova = Self::gerar_senha(10, true, true, true);
        $ftpLink   = "ftp.".$site;

        $user      = explode(".", $site);
        $user      = $user[0];
        
        //fazendo a chamada da senha
        $retorno = $ftp->alteraSenhaFtp(array("idDominio" => $id, "senha" => $senhaNova));

        if(strtolower($retorno['status']) == 'ok'){
            return array('erro'=>false, 'ftp'=>$ftpLink, 'user'=>$user, 'pass'=>$senhaNova);
        }else{
            return array('erro'=>true, 'data'=>$retorno.$senhaNova);
        }
    }

    /**
     * gerar_senha(6, false, true, true)
     * @param $tamanho
     * @param $maiuscula
     * @param $minuscula
     * @param $numeros
     * @return string
     */
    public function gerar_senha ($tamanho, $maiuscula, $minuscula, $numeros)
    {
    	$maius = "ABCDEFGHJKMNPQRSTUWXYZ";
        $minus = "abcdefghjkmnpqrstuwxyz";
        $numer = "23456789";
        $base = '';
        $base .= ($maiuscula) ? $maius : '';
        $base .= ($minuscula) ? $minus : '';
        $base .= ($numeros) ? $numer : '';
        $contat = '';
        
        if($numeros && $tamanho > 8){
        	$tamanho = $tamanho - 2;
        	for ($i = 0; $i < 2; $i++) {
	            $contat .= substr($numer, rand(0, strlen($numer)-1), 1);
	        }
        }

        //srand((float) microtime() * 10000000);

        $senha = '';
        for ($i = 0; $i < $tamanho; $i++) {
            $senha .= substr($base, rand(0, strlen($base)-1), 1);
        }
        //$senha = strtolower($senha);
        return $senha.$contat;
    }


}