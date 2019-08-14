<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Front\Documents;

use Illuminate\Support\Facades\Auth;
use File;


class DocumentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Documents::orderBy('order', 'asc')->get();
        return view('admin.documents.index',['documents'=>$documents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.documents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $this->validate($request, [                                                                                  //Проверка на валидность
                'order' =>  'required|integer',
                'name'  =>   'required',
                'thumb' => 'required|image|mimes:jpeg,png,jpg',
                'link' => 'required|mimetypes:application/pdf',
            ]);
            
            $doc = new Documents();
            $doc->order =strip_tags($request->order);
            $doc->name = strip_tags($request->name);
            
            $image = $request->thumb->getClientOriginalName();                  //Получаем имя изображения
            $imageX = explode('.', $image);                                     //Разбиваем строку
            $hash = hash('md5', $imageX[0].  time());                           //Хэшируем имя картинки
            $imageX[0] = 'doc_' . $hash;                                        //Записываем в массив
            $imageX = 'documents/' . implode('.', $imageX);                     //Конкатенируем строку
            $upload = public_path('/documents');                                //Записываем путь где будем хранить изображения
            $image = $request->thumb;
            $image->move($upload, $imageX);
            $doc->thumb = $imageX;
            
            $pdf = 'documents/' . $request->link->getClientOriginalName();
            $link = $request->link;
            $link->move($upload,$pdf);
            $doc->description = $request->link->getClientOriginalName();
            $doc->link = $pdf;
            
            $doc->save();
            return redirect(route('documents.index'))->with('success', 'Документ успешно добавлен.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = Documents::find($id);
        if (!$document) { 
            return redirect()->back(); 
        }
        return view('admin.documents.show',['document'=>$document]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document = Documents::find($id);
        if (!$document) { 
            return redirect()->back(); 
        }
        return view('admin.documents.edit',['document'=>$document]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $document = Documents::find($id);
        if (!$document) { 
            return redirect()->back(); 
        }
        
        $this->validate($request, ['order' =>  'required|integer', 'name'  =>   'required' ]);
        $document->order =strip_tags($request->order);
        $document->name =strip_tags($request->name);
        $document->viewed = $request->viewed?1:0;
        
        if ($request->thumb) {
            $this->validate($request, [  'thumb' => 'required|image|mimes:jpeg,png,jpg' ]);
                if(file_exists(public_path($document->thumb))){                                                     //Удаляем старое изображение
                    File::delete($document->thumb);
                }  
            $image = $request->thumb->getClientOriginalName();                  //Получаем имя изображения
            $imageX = explode('.', $image);                                     //Разбиваем строку
            $hash = hash('md5', $imageX[0]);                                    //Хэшируем имя картинки
            $imageX[0] = 'doc_' . $hash;                                        //Записываем в массив
            $imageX = 'documents/' . implode('.', $imageX);                     //Конкатенируем строку
            $upload = public_path('/documents');                                //Записываем путь где будем хранить изображения
            $image = $request->thumb;
            $image->move($upload, $imageX);
            $document->thumb = $imageX;
        }
        
        if($request->link) {
            $this->validate($request, [ 'link' => 'required|mimetypes:application/pdf' ]);
                if(file_exists(public_path($document->link))){                                                     //Удаляем старое изображение
                    File::delete($document->link);
                } 
            $pdf = 'documents/' . $request->link->getClientOriginalName();
            $link = $request->link;
            $link->move(public_path('/documents'),$pdf);
            $document->description = $request->link->getClientOriginalName();
            $document->link = $pdf;
        }
        $document->save();
        return redirect(route('documents.index'))->with('success', 'Документ успешно обновлён.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ((Auth::user()->isSuperAdmin() !== 1)) { 
            return redirect()->back(); 
        }
        $doc = Documents::find($id);
        if(file_exists(public_path($doc->image_link))){                         //Удаляем старое изображение
            File::delete($doc->thumb);
        }
        if(file_exists(public_path($doc->link))){                               //Удаляем старый документ
            File::delete($doc->link);
        }
        $doc->delete();
        return redirect(route('documents.index'))->with('success','Документ успешно удален');
    }
}
