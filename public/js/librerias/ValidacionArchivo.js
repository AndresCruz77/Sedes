
class ValidacionArchivo{

    constructor(archivoCargado){
        this.erroresCamposEstructura = [];
        this.camposRequeridosEstructura = [];
        this.encabezadoArchivo = {};
        
        this.leerDocumento(archivoCargado);
    }

    leerDocumento(archivoCargado){
        
        const reader = new FileReader();

        if(reader.readAsBinaryString){
            reader.onload = (e) => {                
                this.procesarArchivo(e.target.result);
            }
            
            reader.readAsBinaryString(archivoCargado.files[0]);
        } 
    }

    procesarArchivo(data){

        //Recibe todos los datos referentes al documento cargado
        const libroCargado = XLSX.read(data, {
            type: 'binary'
        });         

        //Seleccionar los elementos de la perimera hoja del libro
        const primerLibro = libroCargado.SheetNames[0];
    
        //Leer todas las filas desde la primera
        const filasLibro = XLSX.utils.sheet_to_row_object_array(libroCargado.Sheets[primerLibro]);
    
        //Primer fila del archivo para hacer la validacion de campos
        this.encabezadoArchivo = filasLibro[0];

        //validar campos requeridos
        if(!this.validarCamposRequeridos()){
            alert('Archivo procesado con errores');
            console.log(this.erroresCamposEstructura);
            return;
        }else{
            console.log('Todo ok');
        }
    
        //console.log(filasLibro.length);        
    }
    
    validarCamposRequeridos(){
    
        let validacionCampos = true;
    
        this.camposRequeridosEstructura.forEach((campo) =>{
                
            if( !Object.keys(this.encabezadoArchivo).find((key) => key === campo ) ){
                this.erroresCamposEstructura.push({campo: campo, error: 'El campo es obligatorio, no se encuentra en la estructura.'});
                validacionCampos = false;
            }
    
        });

        return validacionCampos;
    }    

}