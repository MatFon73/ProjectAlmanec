var SelectDepart = document.getElementById("SelectDepart");
var SelectCity = document.getElementById("SelectMuni");

fetch('../data/colombia.json')
    .then(response => response.json())
    .then(data => {
        for (var j = 0; j => data.legth; j++) {
            var optionDepart = document.createElement("option");
            optionDepart.text = (data[j].departamento);
            optionDepart.value = (data[j].id);
            optionDepart.classList.add("form-control");
            SelectDepart.add(optionDepart);
        }
    })
    .catch(error => {
        console.log('Error al obtener los datos:', error);
    });

SelectDepart.onchange = function () {
    SelectCity.innerHTML = "";
    if (SelectDepart.value !== "") {
        fetch('../data/colombia.json')
            .then(response => response.json())
            .then(data => {
                var ciudadesArray = (data[SelectDepart.value].ciudades);
                ciudadesArray.forEach(function (ciudad) {
                    var optionCity = document.createElement("option");
                    optionCity.text = (ciudad).trim();
                    optionCity.value = (ciudad).trim();
                    optionCity.classList.add("form-control");
                    SelectCity.add(optionCity);
                })
            })
            .catch(error => {
                console.log('Error al obtener los datos:', error);
            });
        SelectCity.disabled = false;

    } else {
        SelectCity.disabled = true;
    }
}