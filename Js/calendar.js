document.addEventListener('DOMContentLoaded', function() {
    
    const eventColors = [
        { bg: '#3788d8', border: '#3788d8' }, 
        { bg: '#28a745', border: '#28a745' }, 
        { bg: '#dc3545', border: '#dc3545' }, 
        { bg: '#ffc107', border: '#ffc107' }, 
        { bg: '#6f42c1', border: '#6f42c1' }, 
        { bg: '#fd7e14', border: '#fd7e14' },
        { bg: '#20c997', border: '#20c997' }, 
        { bg: '#e83e8c', border: '#e83e8c' }  
    ];
    
    let selectedColor = eventColors[0]; 
    
    
    function createColorPickerHtml() {
        let colorPickerHtml = '<div class="color-picker">';
        eventColors.forEach((color, index) => {
            const isSelected = color.bg === selectedColor.bg;
            colorPickerHtml += `<div class="color-option ${isSelected ? 'selected' : ''}" 
                                   style="background-color: ${color.bg};" 
                                   data-index="${index}"></div>`;
        });
        colorPickerHtml += '</div>';
        
        
        colorPickerHtml += `
            <div class="custom-color-option">
                <input type="color" id="custom-color" class="custom-color-input" value="#3788d8">
                <label for="custom-color" class="custom-color-label">Color personalizado</label>
            </div>
        `;
        
        return colorPickerHtml;
    }
    
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        displayEventTime: false,  
        eventDisplay: 'block',    
        headerToolbar: {  
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay' 
        },
        locale: 'es',
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },
        
        
        events: function(info, successCallback, failureCallback) {
            fetch('../Calendario/eventos.php')
                .then(response => {
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Response is not JSON. Check your PHP script for errors.');
                    }
                    
                    return response.json();
                })
                .then(data => {
                    successCallback(data);
                })
                .catch(error => {
                    console.error('Error loading events:', error);
                    failureCallback(error);
                    
                  
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudieron cargar los eventos. Por favor, intente nuevamente más tarde.',
                        icon: 'error'
                    });
                });
        },
        
        dateClick: function(info) {
            Swal.fire({
                title: 'Agregar Evento',
                html: `
                    <div class="form-group">
                        <label for="swal-title">Título</label>
                        <input id="swal-title" class="form-control" placeholder="Título del evento">
                    </div>
                    <div class="form-group mt-3">
                        <label for="swal-description">Descripción</label>
                        <textarea id="swal-description" class="form-control" placeholder="Descripción del evento"></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label for="swal-start">Fecha inicio</label>
                        <input id="swal-start" type="datetime-local" class="form-control" value="${info.dateStr}T00:00">
                    </div>
                    <div class="form-group mt-3">
                        <label for="swal-end">Fecha fin</label>
                        <input id="swal-end" type="datetime-local" class="form-control" value="${info.dateStr}T01:00">
                    </div>
                    <div class="form-group mt-3">
                        <label>Color del evento</label>
                        ${createColorPickerHtml()}
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel',
                    popup: 'swal2-custom-popup'
                },
                focusConfirm: false,
                didOpen: () => {
                    
                    document.querySelectorAll('.color-option').forEach(option => {
                        option.addEventListener('click', function() {
                            
                            document.querySelectorAll('.color-option').forEach(opt => {
                                opt.classList.remove('selected');
                            });
                            
                           
                            this.classList.add('selected');
                            selectedColor = eventColors[this.getAttribute('data-index')];
                        });
                    });
                    
                    
                    const customColorInput = document.getElementById('custom-color');
                    if (customColorInput) {
                        customColorInput.addEventListener('input', function() {
                            
                            document.querySelectorAll('.color-option').forEach(opt => {
                                opt.classList.remove('selected');
                            });
                            
                            
                            const customColor = this.value;
                            selectedColor = { bg: customColor, border: customColor };
                        });
                    }
                },
                preConfirm: () => {
                    const title = document.getElementById('swal-title').value;
                    const description = document.getElementById('swal-description').value;
                    const start = document.getElementById('swal-start').value;
                    const end = document.getElementById('swal-end').value;
                    
                    if (!start || !end) {
                        Swal.showValidationMessage('Por favor ingresa fecha y hora de inicio y fin válidas.');
                        return false;
                    }
                    
                    if (!title) {
                        Swal.showValidationMessage('Por favor ingrese un título');
                        return false;
                    }
                    
                    const formData = new FormData();
                    formData.append('accion', 'agregar');
                    formData.append('titulo', title);
                    formData.append('descripcion', description);
                    formData.append('fecha_inicio', start);
                    formData.append('fecha_fin', end);
                    formData.append('color_fondo', selectedColor.bg);
                    formData.append('color_borde', selectedColor.border);
                    
                    return fetch('../Calendario/eventos.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            
                            const newEvent = {
                                id: data.id,
                                title: title,
                                start: start,
                                end: end,
                                allDay: false,
                                description: description,
                                backgroundColor: selectedColor.bg,
                                borderColor: selectedColor.border
                            };
                            
                            calendar.addEvent(newEvent);
                            
                            
                            setTimeout(() => {
                                Swal.fire({
                                    title: 'Registrado',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'swal2-popup',
                                        title: 'swal2-title',
                                        icon: 'swal2-icon'
                                    }
                                });
                            }, 500);
                            
                            return true;
                        } else {
                            Swal.showValidationMessage(`Error: ${data.error}`);
                            return false;
                        }
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Error: ${error.message}`);
                        return false;
                    });
                }
            });
        },

        
        eventClick: function(info) {
            const currentColor = {
                bg: info.event.backgroundColor,
                border: info.event.borderColor
            };
            
            let currentColorIndex = 0;
            eventColors.forEach((color, index) => {
                if (color.bg === currentColor.bg) {
                    currentColorIndex = index;
                }
            });
            
            let colorPickerHtml = '<div class="color-picker">';
            eventColors.forEach((color, index) => {
                const isSelected = index === currentColorIndex;
                colorPickerHtml += `<div class="color-option ${isSelected ? 'selected' : ''}" 
                                       style="background-color: ${color.bg};" 
                                       data-index="${index}"></div>`;
            });
            colorPickerHtml += '</div>';
            
            colorPickerHtml += `
                <div class="custom-color-option">
                    <input type="color" id="custom-color" class="custom-color-input" value="${currentColor.bg}">
                    <label for="custom-color" class="custom-color-label">Color personalizado</label>
                </div>
            `;
            
            const formatForInput = dateObj => { 
                const tzOffset = dateObj.getTimezoneOffset() * 60000; 
                const localISO = new Date(dateObj - tzOffset).toISOString().slice(0,16); 
                return localISO;
            }; 
            
            Swal.fire({
                title: 'Editar Evento',
                html: `
                    <div class="form-group">
                        <label for="swal-title">Título</label>
                        <input id="swal-title" class="form-control" value="${info.event.title}">
                    </div>
                    <div class="form-group mt-3">
                        <label for="swal-description">Descripción</label>
                        <textarea id="swal-description" class="form-control">${info.event.extendedProps.description || ''}</textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label for="swal-start">Fecha inicio</label>
                        <input id="swal-start" type="datetime-local" class="form-control" 
                               value="${formatForInput(info.event.start)}">
                    </div>
                    <div class="form-group mt-3">
                        <label for="swal-end">Fecha fin</label>
                        <input id="swal-end" type="datetime-local" class="form-control" 
                               value="${formatForInput(info.event.end || info.event.start)}">
                    </div>
                    <div class="form-group mt-3">
                        <label>Color del evento</label>
                        ${colorPickerHtml}
                    </div>
                `,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                denyButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'swal2-confirm',
                    denyButton: 'swal2-deny',
                    cancelButton: 'swal2-cancel',
                    popup: 'swal2-custom-popup'
                },
                focusConfirm: false,
                didOpen: () => {
                    
                    document.querySelectorAll('.color-option').forEach(option => {
                        option.addEventListener('click', function() {
                            
                            document.querySelectorAll('.color-option').forEach(opt => {
                                opt.classList.remove('selected');
                            });
                            
                            
                            this.classList.add('selected');
                            selectedColor = eventColors[this.getAttribute('data-index')];
                        });
                    });
                    
                    
                    const customColorInput = document.getElementById('custom-color');
                    if (customColorInput) {
                        customColorInput.addEventListener('input', function() {
                            
                            document.querySelectorAll('.color-option').forEach(opt => {
                                opt.classList.remove('selected');
                            });
                            
                            
                            const customColor = this.value;
                            selectedColor = { bg: customColor, border: customColor };
                        });
                    }
                },
                preConfirm: () => { 
   const title       = document.getElementById('swal-title').value; 
   const description = document.getElementById('swal-description').value; 
   const start       = document.getElementById('swal-start').value; 
   const end         = document.getElementById('swal-end').value; 
 
   if (!start || !end) { 
     Swal.showValidationMessage('Por favor ingresa fecha y hora de inicio y fin válidas.'); 
     return false; 
   } 
   
   const formData = new FormData(); 
   formData.append('accion',      'actualizar'); 
   formData.append('id',          info.event.id); 
   formData.append('titulo',       title);         
   formData.append('descripcion',  description);   
   formData.append('fecha_inicio', start); 
   formData.append('fecha_fin',    end); 
   formData.append('color_fondo',  selectedColor.bg); 
   
   
 
   return fetch('../Calendario/eventos.php', { 
     method: 'POST', 
     body: formData 
   }) 
   .then(response => { 
     console.log('Status:', response.status); 
     return response.text(); 
   }) 
   .then(text => { 
     console.log('RAW response:', text); 
     return JSON.parse(text); 
   }) 
   .then(data => { 
     if (!data.success) { 
       
       throw new Error(data.error || 'Sin respuesta del servidor'); 
     } 
     
     info.event.setProp('title',           title); 
     info.event.setExtendedProp('description', description); 
     info.event.setStart(start); 
     info.event.setEnd(end); 
     info.event.setProp('backgroundColor', selectedColor.bg); 
    
 
     return true;  
   }) 
   .catch(err => { 
     Swal.showValidationMessage(`Error al actualizar: ${err.message}`); 
     return false; 
   }); 
},
                preDeny: () => {
                    
                    const formData = new FormData();
                    formData.append('accion', 'eliminar');
                    formData.append('id', info.event.id);
                    
                    return fetch('../Calendario/eventos.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            
                            info.event.remove();
                            
                            
                            Swal.fire({
                                title: 'Eliminado',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'swal2-popup',
                                    title: 'swal2-title',
                                    icon: 'swal2-icon'
                                }
                            });
                            
                            return true;
                        } else {
                            Swal.showValidationMessage('Error al eliminar el evento');
                            return false;
                        }
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Error: ${error.message}`);
                        return false;
                    });
                }
            });
        },
        
      
        editable: true,
        eventResizableFromStart: true,
        eventDurationEditable: true,
        
        
        eventDrop: function(info) {
            updateEventDates(info.event);
        },
        eventResize: function(info) {
            updateEventDates(info.event);
        }
    });
    
    
    function updateEventDates(event) {
        const formData = new FormData();
        formData.append('accion', 'actualizar');
        formData.append('id', event.id);
        formData.append('titulo', event.title);
        formData.append('descripcion', event.extendedProps.description);
        formData.append('fecha_inicio', event.start.toISOString());
        formData.append('fecha_fin', event.end ? event.end.toISOString() : event.start.toISOString());
        formData.append('color_fondo', event.backgroundColor);
        
        
        fetch('../Calendario/eventos.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo actualizar el evento.',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: `Error: ${error.message}`,
                icon: 'error'
            });
        });
    }

    calendar.render();
});