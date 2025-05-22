$(document).ready(function () {
    $(".eliminar-btn").click(function () {
        if (confirm("¿Seguro que quieres eliminar este abogado?")) {
            let id = $(this).data("id");
            $.ajax({
                url: "eliminar.php",
                type: "POST",
                data: { id: id },
                success: function (respuesta) {
                    if (respuesta === "OK") {
                        $("#fila-" + id).fadeOut();
                    } else {
                        alert("Error al eliminar.");
                    }
                }
            });
        }
    });
});
