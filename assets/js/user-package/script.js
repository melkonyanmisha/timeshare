jQuery(document).ready(function ($) {
    //If current page is "User Groups"
    if (pagenow === 'edit-' + timeshareMain.userGroupTaxonomy) {
        const usersCounts = $('.posts.column-posts');
        const usersCountLinks = $(usersCounts).find('a');

        if (usersCountLinks.length) {
            usersCountLinks.map((index, el) => {
                //Replace a tag with p into Count column
                $($(usersCounts)[index]).html(`<p>${el.text}</p>`);
            })
        }
    }
});