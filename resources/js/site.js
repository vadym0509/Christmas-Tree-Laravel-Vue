$(document).ready(() => {

    function dropdown($selector) {
        $($selector).each(function () {
            const dropdownContainer = $(this);

            $(document).mouseup(e => {
                if (!dropdownContainer.is(e.target)
                    && dropdownContainer.has(e.target).length === 0) {
                    dropdownContainer.removeClass('show')
                }
            });

            dropdownContainer.find('.js-dropdown-title').click(e => {
                e.preventDefault();
                dropdownContainer.hasClass('show') ? dropdownContainer.removeClass('show') : dropdownContainer.addClass('show');
            });
            dropdownContainer.find('.js-dropdown-close').click(e => {
                e.preventDefault();
                dropdownContainer.removeClass('show');
            });
            dropdownContainer.find('.js-dropdown-item').click(e => {
                const self = e.target;
                e.preventDefault();
                dropdownContainer.find('.js-dropdown-title-text').text($(self).text());
                dropdownContainer.removeClass('show')
            });
        });
    }

    dropdown('.js-dropdown');

    dropdown('.js-dropdown-currency');

    //delivery date picker
    $('#deliveryDate').datepicker({
        useCurrent: false,
        startDate: '+1d',
        format: 'dd/mm/yyyy',
    });
});
