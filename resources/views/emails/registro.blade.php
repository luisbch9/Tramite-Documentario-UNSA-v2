<!DOCTYPE html> 
  <head> 
  </head> 
 
  <body> 
        <nav class="navbar navbar-default default-primary-color" > 
          <table>
          

            <td>
              <div align="center">
                <h2>Tramite Documentario</h2> 
                
              </div>
            </td>
          </table>
          
       
        </nav> 

        <div class="container">
          @if(isset($email))
            Hola, {{$nombre}} <br>
            Bienvenido a tramite Documentario.
            <br>
            Puede activar su cuenta con este <a href="{{url('/usuarios/'.$email.'/activar')}}">enlace.</a>
          @else
            <h2>No perteneces aquí</h2>
          @endif
        </div>
  </body> 
</!DOCTYPE>