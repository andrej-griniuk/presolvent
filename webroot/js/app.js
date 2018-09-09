
$('#state').on('change', function() {
    var state = this.value;
    var $gccsa = $('#gccsa').html('');
    for (var i in states[state]) {
        $gccsa.append('<option value="' + i + '">' + i + '</option>');
    }
    $gccsa.triggerHandler('change');
}).triggerHandler('change');

$('#gccsa').on('change', function() {
    var state = $('#state').val();
    var gccsa = this.value;
    var $sa = $('#sa').html('');
    for (var i in states[state][gccsa]) {
        var sa = states[state][gccsa][i];
        $sa.append('<option value="' + sa + '">' + sa + '</option>');
    }
}).triggerHandler('change');

$('#submit').on('click', function (e) {
    $(this).closest('form').submit();
});

$('#form').on('submit', function (e) {
    e.preventDefault();

    $('body').toggleClass('loading', true);
    var $form = $(this);
    $.ajax({
        type: 'POST',
        url: $form.attr('action'),
        data: $form.serialize(), // serializes the form's elements.
        dataType: 'json',
        success: function(data)
        {
            $('body').toggleClass('loading', false);
            console.log(data);

            var $result = $('#result');
            if (data['prediction'] !== '') {
                $result.html('<div class="row danger" style="width:100%"><div class="col-xs-12"></div><div class="col-xs-12"><h1>Achtung!!! <strong>' + data['prediction'] + '</strong></h1><div class="pure-steps_preload"><i class="fa fa-times"></i></div></div></div>');
            } else {
                var probability = '';
                for (var i in data['probabilities']) {
                    if (i !== '' && data['probabilities'][i] > 12) {
                        probability = i;
                        break;
                    }
                }
                if (probability !== '') {
                    $result.html('<div class="row warning" style="width:100%"><div class="col-xs-12"></div><div class="col-xs-12"><h1>Warning! <strong>' + probability + '</strong></h1><div class="pure-steps_preload"><i class="fa fa-exclamation"></i></div></div></div>');
                } else {
                    $result.html('<div class="row" style="width:100%"><div class="col-xs-12"></div><div class="col-xs-12"><h1><strong>All good!</strong></h1><div class="pure-steps_preload"><i class="fa fa-check"></i></div></div></div>');
                }
            }
        }
    });

    return false;
})
