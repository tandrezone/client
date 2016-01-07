<?php
class client{
  public $fonts;
  public $styles;
  public $scripts;
  public $headerFiles;
  function getConf(){
    $conf = file_get_contents("packages/moonlight/client/client.json");
    $confD = json_decode($conf);
    $vendorDir = $confD->config->vendorDir;
    $require = $confD->require;
    foreach ($require as $app => $version) {
      $appN = new stdClass();
      $appN->name = $app;
      $appN->version = $version;
      $apps[] = $appN;
      $url ="packages/moonlight/client/".$appN->name."-".$appN->version;
      if(file_exists($url."/loadApp.json")){
        $appf = file_get_contents($url."/loadApp.json");
        $appfD = json_decode($appf);
        $files = $appfD->files;
        foreach ($files as $type => $file) {
          switch ($type) {
            case 'js':
              $this->scripts[] = $url.$file;
            break;
            case 'css':
              $this->styles[] = $url.$file;
            break;
            case 'fonts':
              $this->fonts[] = $url.$file;
            break;
          }
        }
      }
    }
  }
  function constroi(){
    foreach ($this->scripts as $script) {
      $this->headerFiles .= "<script scr='/".$script."'></script>\n";
    }
    foreach ($this->styles as $style) {
      $this->headerFiles .= "<link href='/".$style."' rel='stylesheet'>\n";
    }
  }
  function getHeaderFiles(){
    $this->getConf();
    $this->constroi();
    return $this->headerFiles;
  }
}
