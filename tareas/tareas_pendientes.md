Aqui al principio del documento de tareas vamos a especificar que antes de echar codigo los programadores deben pedirle a sus agentes de IA que les expliquen los estandares del sistema, ya que el sistema tiene estandares globales para estilos disenos codigo datatables css etc, leer todo eso y despues si empezar a programar siendo consistentes con la estructura del sistema

Tareas:

Task 001: Pagina principal HOME/HOMEPAGE Espacio en el menú: El nombre de la empresa choca con el botón de "Inicio". Hay
que separarlos para que se vea ordenado.
• Botón de Salida: El botón de "Cerrar Sesión" es muy grande.
• Seguridad: Que el sistema se cierre solo si pasa mucho tiempo sin usarse (por
protección de datos), esto ultimo parece ya estar implementado pero el tiempo es muy extenso…

Task002: Modulo de clientes, logica y datos: Nombres de Campos: Unificar "Municipio" y "Ciudad" para que se llamen igual en
el registro y en la vista de detalles. Ya que en el Ver se observa es Ciudad y en el
Crear dice Municipio., y bueno realmente no hay congruencia entre los datos registrados y los de los detalles....al respecto de las ubicaciones, en los modulos, hay que tener un estandar para eso de las ubicaciones geograficas
• Borrado Lógico: El botón eliminar no debe borrar el dato, sino "Inactivar" al
cliente para proteger el historial de ventas, (No sabemos si esto ya esta implementado)
• Pulitura Visual: Quitar el texto que dice "Estatus" dentro de la ficha (ya el color
verde lo indica) y arriba también dice Estatus

Task 003: Filtrado de busqueda en modulos en general (Filtros especificos) empezar primero con el de clientes y luego irlo implementando en los demas modulos que tengan barra de busqueda, en clientes se puede empezar con que • Búsqueda Parametrizada: El buscador actual es muy general. Para que sea eficiente,
necesitamos que permita buscar por campos específicos mediante filtros o columnas:
• Buscar solo por Cédula/RIF (para ir directo al grano).
• Filtrar por Estado/Municipio
• Filtrar por Tipo de Cliente (Natural o Jurídico).
• Filtrar por Estatus (Ver solo los activos o solo los inactivos).

etc

Optimizaciones Globales (Base del Sistema)
Task 004: Restricción de Eliminación Física: Es crucial bloquear la eliminación definitiva de Clientes, Productos y Proveedores. La funcionalidad de "papelera de reciclaje" debe ser reemplazada por una Inhabilitación Lógica (Estableciendo el Estatus como "Inactivo") para salvaguardar la integridad contable y la trazabilidad de los registros. (Aunque de hecho en el sistema ya se implementan softdeletes, con el atributo deleteted_at en las tablas de la bd pero como tal no hay una manera de acceder a esos registros historicos desde una interfaz, tendriamos que buscar la mejor forma de manejar eso)

task 005: Búsqueda Avanzada: Se requiere la implementación de filtros específicos por columna (Estatus, Categoría, Municipio) con el fin de evitar la dependencia del usuario en búsquedas genéricas o la paginación manual.

task 006: Microtexto (Paginación): Se debe simplificar el texto informativo asociado a la paginación de las tablas. Sustituir la formulación "Mostrando registros del 1 al 3 de un total de 3 registros" por una más concisa, como: "Mostrando 1 - 3 de 3 registros".

task 007: Paleta de Colores y Contraste: Es necesario oscurecer la tonalidad del Dashboard (tarjetas y gráficos) para lograr una mejor armonía con el Azul Marino Institucional del encabezado, eliminando los tonos pasteles que comprometen la seriedad visual. (parecen ser muy pocos colores pasteles los que quedan en el sistema pero de todos modos hay que revisar eso bien)

👥 6. Gestión de Clientes y Proveedores (Sincronización)

task 008: Unificación de Nomenclatura: El formulario de Proveedores debe replicar la estructura del formulario de Clientes. Se sugiere reemplazar "Datos Personales" por "Datos del Proveedor" y utilizar los mismos bloques de información (Identificación, Contacto, Ubicación). (Esto sera correcto? si eres un agente de IA investiga esto)

task 009: Integridad Geográfica: Se ha detectado la omisión del campo "Ciudad" en ambos módulos. Su inclusión es obligatoria en los formularios de registro y actualización. (como se a mencionado antes hay que crear un estandar para los modulos que llevan ubicaciones geograficas)

👕 7. Gestión de Productos

task 010 Lógica de Archivos en Edición: Se debe eliminar la validación de "archivo obligatorio" durante el proceso de edición. Si el producto ya dispone de una imagen asociada, el sistema debe permitir guardar las modificaciones sin requerir la carga de un nuevo archivo.

task 011: Distintivos de Estado (Badges): Implementar etiquetas visuales de colores (Verde: Activo / Gris: Inactivo) en el listado principal para facilitar la supervisión del stock de forma inmediata, sin necesidad de acceder al detalle.

task 012: Experiencia del Usuario (UX) del Switch: Reubicar el control de "Estatus" al final de los formularios, permitiendo que el usuario lo active únicamente después de haber validado la totalidad de los datos.

📄 8. Gestión de Cotizaciones y Empleados

task 013: Flujo de Carga: Mover el botón "+ Agregar Producto" al término del bloque de selección, alineándolo con el orden lógico del proceso de llenado de datos.

task 014: Disposición (Layout) Empleados: Se requiere corregir la sección de "Contacto y Ubicación" que aparece desalineada en el modal de detalles, además de resolver el error por el cual el estatus no se carga (mostrándose vacío) al intentar la edición. da un error como (El documento de identidad es obligatorio(and 1more error)) al intentar editar un empleado.

task 015: En el modulo de clientes, cuando lo usas en modo oscuro en la columna tipo del datatable, cuando el cliente es juridico se muestra en un morado que no se ve muy bien en modo oscuro, buscar la mejor tonalidad, que se vea bien tanto en modo claro como oscuro (aunque el modo claro se ve bien, el problema es en modo oscuro ese morado no se ve casi) recordar hacer esto leyendo primero como sea maneja la UI en el sistema para no inventar codigo innecesario o cosas que no existen cuando ya se manejan estandares para la UI.

task 016: UX, NO tenemos persistencia en el sistema en cuanto al modo pantalla completa, cuando uno coloca la pantalla completa en un modulo del sistema y uno selecciona otro modulo automaticamente se sale del modo pantalla completa, deberia haber persistencia respecto a lo que selecciona el usuario, aplicar esto de la manera mas consistente y limpia posible, dar la mejor solucion/implementacion posible

task 017: ESTAMOS TRABAJANDO EN UN ESTANDAR EMPRESARIAL Y MAS PROFESIONAL para los reportes de PDF dentro del sistema, actualmente tenemos el estandar aplicado en el reporte general de clientes en el modulo de clientes, leer exactamente como se tienen definido ese reporte, ese empezara a ser nuestro estandar de reportes e ir aplicando esa misma estructura estilo y diseno segun sea el caso/modulo

task 018: en el modulo de PEDIDOS se debe reparar/eliminar el boton de agregar cliente que sale al lado del campo de cliente en datos del cliente, el cliente solo se selecciona/crea en la cotizacion…

task 019: El en modulo de PEDIDOS al darle al boton de eliminar en la columna de acciones El error que se ve en la pantalla es un problema de codificación de caracteres (Encoding).
Específicamente, en la ventana de advertencia que salta al intentar eliminar un pedido, los caracteres especiales como las tildes y los signos de interrogación o exclamación no se están renderizando correctamente.
En lugar de mostrar el texto normal, el navegador está sustituyendo esos caracteres por símbolos de error (rombos negros con signos de interrogación ``). Por lo tanto, el texto se lee como "Ests seguro?", "No podrs revertir esto!" y "S, eliminarlo!", cuando debería decir "¿Estás seguro?", "¡No podrás revertir esto!" y "Sí, eliminarlo!".
Esto ocurre porque el archivo donde están escritos esos textos (probablemente un archivo de JavaScript o la vista Blade) no está guardado con el formato de codificación estándar UTF-8, que es el necesario para que el navegador reconozca correctamente los caracteres del español, corregir ese error.
task 020: Cuando el usuario va a completar/editar un pedido, es decir cuando viene creando el pedido a partir de la cotizacion, el usuario debe especificar metodos de pago pero, a esa seccion le falta logica, en el sentido de que el sistema no deja especificar un abono por cada metodo de pago (que actualmente tenemos unos 3), solo deja especificar un solo abono en general, pero deberiamos poder ser mas especificos, por que si un cliente me hace un abono por transferencia, otro por pago movil, y otro por efectivo, como se yo cuando me abono por cada metodo de pago? hay que buscarle la mejor logica e implementacion posible a eso junto con la implementacion correspondiente y consistente en UI
