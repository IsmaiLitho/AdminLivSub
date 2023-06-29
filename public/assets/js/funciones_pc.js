
$('#footer_check').on('change', function() {            
    let footer = $('#footer_check').is(':checked');
    let fechain = $('#fechain').val(); 
    let fechafin = $('#fechafin').val();
    let check = true;
    //console.log(fechafin);
    
    if (fechain == '') {
        $('#fechain').addClass('is-invalid');
        $('#sec_col_fechain').append(`<p class="text-danger">La fecha de finalización es requerida</p>`);
        check = false;
        $('#footer_check').prop('checked', false);
    }

    if (fechafin == '') {
        $('#fechafin').addClass('is-invalid');
        $('#sec_col_fechafin').append(`<p class="text-danger">La fecha de finalización es requerida</p>`);
        check = false;
        $('#footer_check').prop('checked', false);
    }

    if (check) {
        data = { 
            fechain:fechain,
            fechafin:fechafin,
            check:check,
        };
        verifyTerminos(data);
    }
});

function verifyTerminos(data) {
    let meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

    let dia_in = moment(data.fechain).format('DD');
    let dia_fin = moment(data.fechafin).format('DD');
    let mes_in = meses[parseInt(moment(data.fechain).format('M')) - 1];
    let mes_fin = meses[parseInt(moment(data.fechafin).format('M')) - 1];

    let tc = '';
    if (mes_in == mes_fin) {
        tc = `Términos y condiciones del ${dia_in} al ${dia_fin} de ${mes_in} del 2023`;
    } else {                
        tc = `Términos y condiciones del ${dia_in} de ${mes_in} al ${dia_fin} de ${mes_fin} del 2023`;
    }

    if (!data.check) {
        $('#sec_tc').find('div#sec_footerLeyenda').remove();
        $('#sec_tc').find('div#sec_archivo_tc').remove();
    } else {
        $('#sec_tc').append(`
            <div class="col-md-5" id="sec_footerLeyenda">
                <label class="form-label">Leyenda de términos y condiciones:</label>
                <input type="text" name="footerLeyenda" id="footerLeyenda" class="form-control" value="${tc}" readOnly>
            </div>
            <div class="col-md-5" id="sec_archivo_tc">
                <label class="form-label">Archivo de términos y condiciones:</label>
                <input type="file" name="archivo_tc" class="form-control" id="archivo_tc">
            </div>
        `);                
    }
    //console.log(tc);
}

$('#check_hora_in').on('change', function() {
    if ($('#check_hora_in').is(':checked')) {
        $('#sec_fechain').append(`
            <div class="col-md-4" id="sec_hora_in">
                <label class="form-label">Hora de publición:</label>
                <input type="time" name="hora_in" id="hora_in" class="form-control">
            </div>
        `);
    } else {
        $('#sec_fechain').find('div#sec_hora_in').remove();
    }
});

$('#check_hora_fin').on('change', function() {
    if ($('#check_hora_fin').is(':checked')) {
        $('#sec_fechafin').append(`
            <div class="col-md-4" id="sec_hora_fin">
                <label class="form-label">Hora de finalización:</label>
                <input type="time" name="hora_fin" id="hora_fin" class="form-control">
            </div>
        `);
    } else {
        $('#sec_fechafin').find('div#sec_hora_fin').remove();
    }
});

$('#fechain').on('change', function() {        
    $('#fechain').removeClass('is-invalid');
    $('#sec_col_fechain').find('p.text-danger').remove();
});

$('#fechafin').on('change', function() {        
    $('#fechafin').removeClass('is-invalid');
    $('#sec_col_fechafin').find('p.text-danger').remove();
});