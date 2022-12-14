var Garantiza = function () {
    return {
        notificaciones: function (mensaje, titulo, tipo) {
            new PNotify({
                title: titulo,
                text: mensaje,
                shadow: true,
                opacity: '0.75',
                addclass: 'stack_top_right',
                type: tipo,
                stack: {
                    "dir1": "down",
                    "dir2": "left",
                    "push": "top",
                    "spacing1": 10,
                    "spacing2": 10
                },
                width: '290px',
                delay: 2000
            })
        },
        alerta: function(){
            alert('alerta');
        }
    }
}();

