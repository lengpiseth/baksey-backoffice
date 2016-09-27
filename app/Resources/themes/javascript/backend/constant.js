var domainName  = document.location.origin;
var siteUrl     = (domainName.match(/127.0.0.1|.dev|.tk/g)).length > 0 ? domainName : domainName + "/baksey";

var constant = {
    api : {
        media: {
            Index : siteUrl + '/admin/media/library'
        }
    }
};
