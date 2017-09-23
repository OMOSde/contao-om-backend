/**
 * Variables
 */
var isKeyDown = false;


/**
 * Events
 */
window.addEvent('domready', function() {
    onDomReady();
});
window.addEvent('resize', function(){
    setToolbarPosition();
});
window.addEvent('keydown', function(event) {
    keyDown(event);
});
window.addEvent('keyup', function(event) {
    keyUp(event);
});


/**
 * Execute at domready
 */
function onDomReady()
{
    // handle element buttons
    $$('.om_elements .button').each(function(item) {
        item.addEvent('click', function() {
            $$('#ctrl_type option[value='+item.get('data-value')+']').set("selected", "selected");
            $$('#ctrl_type').fireEvent('liszt:updated').fireEvent('change');

            document.getElementById("ctrl_type").onchange();
        });
    });

    // add events
    $$('input[type=text]').addEvent('keyup',function() {
        generateCounter();
    });

    // init counter on text fields
    generateCounter();

    // set toolbar position
    setToolbarPosition();
}


/**
 * Set toolbar position
 */
function setToolbarPosition()
{
    marginRight = ($$('html').getSize()[0].x - $$('#container').getSize()[0].x) / 2;
    $$('#om_backend_toolbar').setStyle('right', marginRight + 'px');
}


/**
 *
 * @param event
 */
function keyDown(event)
{
    if (event.code == 16 && !isKeyDown)
    {
        isKeyDown = true;
        $$('.om_backend_id_view .tl_listing .tl_right, .om_backend_id_view .tl_listing .tl_right_nowrap, .om_backend_id_view .tl_content .tl_content_right').each(function(elem) {
            var id = 0;
            elem.getElements('a').some(function(link) {
                var pos = link.get('href').indexOf('&id=');
                if (pos > 0)
                {
                    var part = link.get('href').substr(pos + 4);
                    id = part.substr(0, part.indexOf('&'));

                    return true;
                }
            });

            if (!elem.hasClass('tl_content_right'))
            {
                if (id > 0 && elem.getPrevious().get('html').indexOf('[ID:') <= 0)
                {
                    elem.getPrevious().appendHTML('<span style="color:#b3b3b3;padding-left:3px">[ID: '+id+']</span>');
                }
            } else {
                if (id > 0 && elem.getNext().get('html').indexOf('[ID:') <= 0)
                {
                    elem.getNext().appendHTML('<span style="color:#b3b3b3;padding-left:3px">[ID: '+id+']</span>');
                }
            }
        });
    }
}


/**
 *
 * @param event
 */
function keyUp(event)
{
    if (event.code == 16)
    {
        isKeyDown = false;
        $$('.om_backend_id_view .tl_listing .tl_left, .om_backend_id_view .tl_listing .tl_file_list, .om_backend_id_view .tl_content .tl_content_left, .om_backend_id_view .tl_content .cte_type').each(function(elem) {
            var pos = elem.get('html').indexOf('<span style="color:#b3b3b3;padding-left:3px">[ID:');
            if (pos > 0)
            {
                elem.set('html', elem.get('html').substr(0, pos));
            }
        });
    }
}


/**
 * Generate counter
 */
function generateCounter()
{
    $$('.om_backend_counter_view input[type=text],.om_backend_counter_view textarea').some(function(elem)
    {
        strMax = (elem.get('maxlength') != null) ? ' / '+elem.get('maxlength') : '';
        if (elem.getPrevious() != null)
        {
            elem.getPrevious().getChildren('.length').set('text', '['+elem.value.length+strMax+']')
        }
    });
}