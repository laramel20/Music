<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PlaylistModel;
use App\Models\MusicModel;

class MainController extends BaseController
{
    private $Music;
    private $Playlist;

    function __construct()
    {
        $this->music = new PlaylistModel();
        $this->musicbridge = new MusicModel();
    }
    
    public function index()
    {
        $data=[
            'music'=>$this->music->findAll(),
            'Playlist'=>$this->Playlist->findAll(),
        ];
        return view('Music/index',$data);
    }

    public function UploadMusic()
    {
        $file=$this->request->getFile('MusicFile');
        var_dump($file);

        $newFileName = $file->getName();

        $data = [
            'Music Name'=> $file->getName(),
            'Music File Address'=> $newFileName
        ];

        $rules =[
            'MusicFile' =>[
                'uploaded[MusicFile]',
                'mine_in[MusicFile,audio/mpeg]',
                'max_size[MusicFile,10240]',
                'ext_in[MusicFile,mp3]'
            ]
        ];

        if ($this->validate($rules)) {
            if($file->isValid() &&!$file->hasMoved()) {
                if ($file->move(FCPATH.'uploads\Music',$newFileName)) {
                    echo "File uploaded successfully";
                    $this->music->save($data);
                }
                else {
                echo $file->getErrorString().' '.$file->getError();
                }
            }
        }else{
            $data['validation'] = $this->validator;
        }
        return redirect()->to('/UploadMusic');
    }

}
