<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Empleado;
use App\User;
use App\Cargo;
use App\Area;
use App\Mail\Email;
use App\EstadoEmpleado;
use Mail;



class empleadosController extends Controller
{

    public function antesCrear(){
      $cargos = DB::table('cargos')->select('id','nombreCargo')->get();
      $areas = DB::table('areas')->select('id','nombre')->get();
      $estados = DB::table('estado_empleados')->select('id','nombre')->get();
      return view('empleados.crear',['cargo'=>$cargos, 'area'=>$areas,'estado'=>$estados]);

    }

    public function antesCrearUsu(){
      $cargos = DB::table('cargos')->select('id','nombreCargo')->get();
      $areas = DB::table('areas')->select('id','nombre')->get();
      $estados = DB::table('estado_empleados')->select('id','nombre')->get();
      return view('empleados.usuario',['cargo'=>$cargos, 'area'=>$areas,'estado'=>$estados]);

    }

    public function create(Request $datos){

    	$nuevo = new Empleado;
    	$nuevo->id_area = $datos->id_area;
    	$nuevo->id_cargo = $datos->id_cargo;
      $nuevo->id_estado = $datos->id_estado;
      $nuevo->activo = true;

     	$encontrado = User::where('dni',$datos->dni)->first();
    	
      $mensaje="";
      $mensaje2="";
      if(!$encontrado){
        $mensaje = "No existe ningun usuario con este DNI ".$datos->dni.", por favor verifique si es correcto. Caso contrario diríjase a la pestaña empleado Nuevo";
        return response()->json(["respuesta"=>false,"data"=>$mensaje,"error"=>"dni"]);
      }

      $nuevo->id_persona = $encontrado->id;
      $nuevo->save();

      $user = User::find($encontrado->id);
      $cargo = Cargo::find($datos->id_cargo);
      $area = Area::find($datos->id_area);
      $estado = EstadoEmpleado::find($datos->id_estado);

      $nuevo->cargo()->associate($cargo);
      $nuevo->area()->associate($area);
      $nuevo->estado()->associate($estado);
      $nuevo->user()->associate($user);


      $mensaje = "El usuario ".$user->nombre." ".$user->apellido." ahora es un empleado. Operación satisfactoria"; 
      return response()->json(["respuesta"=>true,"dato"=>$mensaje]);    	
    }

    public function createNew(Request $datosn){
      	$newPer = new User;
        $newPer->nombre = $datosn->nomPer;
        $newPer->apellido = $datosn->apellidoPer;
        $newPer->dni = $datosn->dni;
        $newPer->password = '123';
        $newPer->email = $datosn->correo;
        $newPer->activo = false;

        $email=DB::table('users')->where('email',$datosn->correo)->first();
       //dd($email);
        $dni=DB::table('users')->where('dni',$datosn->dni)->first();

        $mensaje="";
        $mensaje2="";

        if($dni){
            $mensaje ="Un usuario con este DNI ".$datosn->dni." ya esta registrado. ";
            $mensaje2 = "Si este DNI ya es usuario, por favor dirigirse a crear empleado usuario";
            return response()->json(["respuesta"=>false,"data"=>$mensaje,"data2"=>$mensaje2,"error"=>"dni"]);   
        }
        if($email){
            $mensaje ="Un usuario con este correo ".$datosn->correo." ya esta registrado.";
            return response()->json(["respuesta"=>false,"data"=>$mensaje,"error"=>"email"]);
        }

        $newPer->save();
          


      $newEmp = new Empleado;
      $newEmp->id_area = $datosn->id_area;
      $newEmp->id_cargo = $datosn->id_cargo;
      $newEmp->id_estado = $datosn->id_estado;
      $newEmp->id_persona = $newPer->id;
      $newEmp->activo = false;  
      

      $cargo = Cargo::find($datosn->id_cargo);
      $area = Area::find($datosn->id_area);
      $estado = EstadoEmpleado::find($datosn->id_estado);

      $newEmp->cargo()->associate($cargo);
      $newEmp->area()->associate($area);
      $newEmp->estado()->associate($estado);
      $newEmp->user()->associate($newPer);

      $newEmp->save();

      $correo=new Email;
      $correo->nombre=$datosn->nomPer;
      $correo->email=$datosn->correo;
      $correo->empleado=true;
      Mail::to($datosn->correo)->send($correo);



     return response()->json(["respuesta"=>true,"data"=>"Empleado ".$datosn->nomPer." ".$datosn->apellidoPer." creado con éxito !."]);


    }

    public function todos(){
      $todos = Empleado::with('user','cargo','area','estado')->get()    ;
      
      //dd($join);
      return response()->json($todos);
    }  



    public function show($id){
      $encontrado = Empleado::findOrFail($id);
      if ($encontrado == null) {
        return view ('errors.noEmpleado');
      }
//      echo $encontrado->area->nombre;
    return view('empleados.show',['empleado'=>$encontrado]);
    }



    public function editar($id){
        $encontrado = Empleado::findOrFail($id);
        if ($encontrado == null) {
            return view ('errors.noEmpleado');
        }

      $cargos = DB::table('cargos')->select('id','nombreCargo')->get();
      $areas = DB::table('areas')->select('id','nombre')->get();
      $estados = DB::table('estado_empleados')->select('id','nombre')->get();

        return view('empleados.editar',['empleado'=>$encontrado,'cargos'=>$cargos,'areas'=>$areas,'estados'=>$estados]);
    }

        public function guardar(Request $datos,$id){
        $editar = Empleado::find($id);
        $editarUsu = User::find($editar->id_persona);

        $editar->id_area = $datos ->area;
        $editar ->id_cargo = $datos ->cargo;
        $editar ->id_estado = $datos ->estado;
        $editar ->save();

        $editarUsu->nombre = $datos->nombre;
        $editarUsu->apellido = $datos->apellido;
        $editarUsu->email = $datos->email;
        $editarUsu->dni = $datos->dni;
        $editarUsu->activo = true;
        echo $editarUsu->id;
        echo $editarUsu->apellido;

        $editarUsu->save();


        return redirect('empleados/'.$id);
    }

    public function eliminar($id){
      $encontrado = Empleado::find($id);
      if ($encontrado == null) {
          
            return view ('errors.noEmpleado');
        }
        return view('empleados.eliminar',['eliminado'=>$encontrado]);
    }

    public function eliminado(Request $datos){
      $eliminado = Empleado::find($datos->id);
      $eliminado->delete();
      return redirect('empleados');
    }


}
