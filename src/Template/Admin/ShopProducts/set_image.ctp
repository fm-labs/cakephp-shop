<style>
    .imagepicker-form select[multiple] {
        min-height: 350px;
    }
</style>
<div class="ui form imagepicker-form" style="padding-bottom: 2em;">
    <button id="imagepicker-on" class="ui basic button"><i class="icon file image outline"></i>Show Thumbnails</button>
    <button id="imagepicker-off" class="ui basic button"><i class="icon list"></i>Show List</button>
    <div class="ui search" id="search-form" style="margin-top: 0.3em;">
        <div class="ui left icon fluid input">
            <i class="search icon"></i>
            <input id="search-input" class="" placeholder="Search..." type="text">
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="ui divider"></div>
<?= $this->Form->create($content, ['url' => [
    'action' => 'setImage',
    'iframe' => $this->request->is('iframe'),
    'scope' => $scope,
    'multiple' => $multiple
]]); ?>
    <?php
    echo $this->Form->input($scope, [
        'type' => 'imageselect',
        'multiple' => $multiple,
        'options' => $imageFiles,
        'class' => 'grouped',
        'id' => 'imagepicker-select',
        'empty' => __d('banana','- Choose Image -'),
    ]); ?>

<?= $this->Form->submit('Save'); ?>
<?= $this->Form->end(); ?>
</div>


<?php $this->append('scriptBottom'); ?>
<script>

    $('#imagepicker-on').click(function(e) {
        e.preventDefault();
        $(this).addClass('loading');
        $('#imagepicker-select').imagepicker({
            show_label: true,
            initialized: function() {

                $(this)[0].picker.addClass('grouped');

                /*
                 $(this)[0].picker.find('img.image_picker_image').each(function() {
                 var $label = $(this).next('p');
                 if ($label.length > 0) {
                 $(this).attr('title', $label.html());
                 }
                 });
                 */

                $('#search-form').show();
                $('#imagepicker-off').show();
                $('#imagepicker-on').removeClass('loading');
                $('#imagepicker-on').hide();
            }
        });
        $('#search').show();
    });

    $('#imagepicker-off').click(function(e) {
        e.preventDefault();

        $("#imagepicker-select").data('picker').destroy();
        $('#search-form').hide();
        $('#imagepicker-on').show();
        $(this).hide();
    })

    $('#search-input').on('keyup', function(e) {
        var val = $(this).val();
        //console.log("search: " + val);

        if (val.length < 1) {
            $('.image_picker_selector ul li').show();
            return;
        }

        $('.image_picker_selector ul li').hide();

        $('.image_picker_selector').find('ul li .thumbnail p').each(function() {
            var item = $(this).html();
            if (item.indexOf(val) > 0) {
                //console.log($(this).html() + " contains " + val);
                $(this).parent().parent().show();
            }

        });


    });

    $(document).ready(function() {
        $('#search-form').hide();
        $('#imagepicker-off').hide();
        //$('#imagepicker-on').trigger('click');
    });

</script>
<?php $this->end(); ?>