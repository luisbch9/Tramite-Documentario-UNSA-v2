<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Empleado;
use App\User;
use App\Cargo;
use App\Area;

class empleadosController extends Controller
{

    public function antesCrear(){
      $cargos = DB::table('cargos')->select('id','nombreCargo')->get();
      $areas = DB::table('areas')->select('id','nombre')->get();
      return view('empleados.crear',['cargo'=>$cargos, 'area'=>$areas]);

    }

    public function create(Request $datos){
    	$nuevo = new Empleado;
    	$nuevo->id_area = $datos->id_area;
    	$nuevo->id_cargo = $datos->id_cargo;
      $nuevo->id_estado = $datos->id_estado;
      $nuevo->activo = false;
     	$encontrado = User::where('dni',$datos->dni)->first();
    	$nuevo->id_persona = $encontrado->id;


      $nuevo->save();
      $user = User::find($encontrado->id);
      $cargo = Cargo::find($datosn->id_cargo);
      $area = Area::find($datosn->id_area);

      $nuevo->cargo()->associate($cargo);
      $nuevo->area()->associate($area);
      $nuevo->user()->associate($user);


      
      return redirect('empleados');    	
    }

    public function createNew(Request $datosn){
      	$newPer = new User;
        $newPer->nombre = $datosn->nomPer;
        $newPer->apellido = $datosn->apellidoPer;
        $newPer->dni = $datosn->dni;
        $newPer->password = bcrypt($datosn->dni);
        $newPer->email = $datosn->correo;
        $newPer->activo = false;
        $newPer->save();
          


      $newEmp = new Empleado;
      $newEmp->id_area = $datosn->id_area;
      $newEmp->id_cargo = $datosn->id_cargo;
      $newEmp->id_estado = $datosn->id_estado;
      $newEmp->id_persona = $newPer->id;
      $newEmp->activo = false;  
      

      $cargo = Cargo::find($datosn->id_cargo);
      $area = Area::find($datosn->id_area);

      $newEmp->cargo()->associate($cargo);
      $newEmp->area()->associate($area);
      $newEmp->user()->associate($newPer);

      $newEmp->save();


      return redirect('empleados');


    }

    public function todos(){

      $join = DB::table('empleados')
                ->leftjoin('users','empleados.id_persona','=','users.id')
                ->get();

      //dd($join);
      return response()->json($join);
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

        return view('empleados.editar',['empleado'=>$encontrado,'cargos'=>$cargos,'areas'=>$areas]);
    }

        public function guardar(Request $datos,$id){
        $editar = Empleado::find($id);
        $editar->area = $datos ->area;
        $editar ->cargo = $datos ->cargo;
        $editar ->activo = $datos ->activo;
        $editar ->save();

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
