let $lastFocus;

function currentTime() {
	let now = moment().format('HH:mm:ss')
  	$('#clock').text(now)
  	let t = setTimeout(function(){ currentTime() }, 1000)
}

function format_money(val, fraction=0) {
    let locale = $('html').attr('lang') == 'id' ? 'id-ID' : 'en-EN'
    let new_val = fraction > 0 ? parseFloat(val).toFixed(fraction) : parseFloat(val)
    return new Intl.NumberFormat(locale, { minimumFractionDigits: fraction }).format(new_val);
}

$(function() {
    currentTime();

    $('.custom-keyboard').on('mousedown', function (e) {
    	e.preventDefault()
    });

    $('.input-rm-formatted').change(function (e) {
        const $obj = $(this)

        let value = $obj.val().replace(/-/g, '')
        
        let splitted = value.match(/.{1,2}/g)
        value = splitted.join('-')
        $obj.val(value)
    })

    $('.custom-keyboard').on('click', function (e) {
    	e.preventDefault()
    	const $obj = $(this)
    	const value = $obj.data('value')
    	let $focused = $(':focus').first();
        
        if ($focused.length === 0) {
            $focused = $lastFocus
        }
        $lastFocus = $focused

    	let inputValue = $focused.val().replace(/-/g, '');
        inputValue = inputValue.replace(/_/g, '');

    	if (value === 'delete') {
    		inputValue = inputValue.slice(0, -1)
    		$focused.val(inputValue)
            $focused.trigger('change');
    	} else if (value === 'enter') {
            const $next = $focused.next('.input-virutal')

            if ($next.length > 1) {
                $next.focus();
            } else {
                $form = $focused.closest('form')
                $form.trigger('submit');
            }
    	} else {
    		inputValue = inputValue + value
    		$focused.val(inputValue)
            $focused.trigger('change');
    	}
    })

    $('#back').on('click', function (e) {
        const $obj = $(this)
        if ($obj.attr("href") === '#') {
            history.back()
        }
    })
});