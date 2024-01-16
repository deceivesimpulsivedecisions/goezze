const $ = require('jquery');

$(function (){
    $('.add_item_link').click(function ()
    {
        const collectionHolder = document.querySelector('.RoomGuests');

        const item = document.createElement('li');

        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );

        collectionHolder.appendChild(item);

        collectionHolder.dataset.index++;

        addTagFormDeleteLink(item);
    })

    document
        .querySelectorAll('ul.RoomGuests li')
        .forEach((tag) => {
            addTagFormDeleteLink(tag)
        })

    const addTagFormDeleteLink = (item) => {
        const removeFormButton = document.createElement('button');
        removeFormButton.innerText = 'Delete this tag';

        item.append(removeFormButton);

        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault();
            // remove the li for the tag form
            item.remove();
        });
    }
});