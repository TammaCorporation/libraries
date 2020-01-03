// Note: displays placeholders in place of contents while 
// contents are being retrieved from a database
// ======================================================
// Implementation: tamma_skeleton_loader('body', ['img.large', 'p.center', 'img.regular*4', 'p.center', 'p.regular*5']);
function tamma_skeleton_loader(target=0, specification=0) {
    // remove all contents from target section
    $(target).html('');
    // skeleton components tree
    const components = {
        img: {
            large:    '<div class="skeleton rounded image mt-2 mb-2"></div>',
            regular:  '<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 p-1 ske-float"><div class="skeleton rounded image-reg "></div></div>',
            circle:   '<div class="skeleton ske-image-round"></div>'
        },
        p: {
            regular: '<p class="skeleton rounded" style="width:100%; height:20px; "></p>',
            short:   '<div class="col-12 p-0"><p class="skeleton rounded" style="width:30%; height:20px;"></p></div>',
            large:   '<p class="skeleton rounded" style="width:100%; height:100px; "></p>',
            center:  '<div class="col-12 p-0"><p class="skeleton rounded" style="width:50%; height:20px; margin:auto; margin-bottom:1%;"></p></div>',
            short_center:  '<div class="col-12 p-0"><p class="skeleton rounded" style="width:20%; height:20px; margin:auto; margin-bottom:1%;"></p></div>',
            small_center:  '<div class="col-12 p-0"><p class="skeleton rounded" style="width:10%; height:20px; margin:auto; margin-bottom:1%;"></p></div>',
        },
        card: {
            small:    '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12 mb-3 ske-float"><div class="card skeleton border-2"><div class="bg-white container text-center" style="height:100px;"><i class="fas fa-image fa-6x text-secondary"></i></div><div class="card-body skeleton"><h4 class="text-center"><p class="bg-white rounded text-white">lorem</p></h4></div></div>',
            medium: '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mb-3 ske-float"><div class="card skeleton border-2"><div class="bg-white container text-center" style="height:100px;"><i class="fas fa-image fa-6x text-secondary"></i></div><div class="card-body skeleton"><h4 class="text-center"><p class="bg-white rounded text-white">lorem</p></h4></div></div>',
            large: '<div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12 mb-3 ske-float"><div class="card skeleton border-2"><div class="bg-white container text-center" style="height:100px;"><i class="fas fa-image fa-6x text-secondary"></i></div><div class="card-body skeleton"><h4 class="text-center"><p class="bg-white rounded text-white">lorem</p></h4></div></div>'
        },
        table: '<table  width="100%"><tr><th> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </th><th> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </th><th> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </th><th> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </th></tr><tr><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td></tr><tr><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td></tr><tr><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td><td> <p class="skeleton sk-p-radius p-3" style="width:100%;"></p> </td></tr></table>',
        article: {
            left: '<div class="col-12 p-0">  <div class="row"> <div class="col-6"><div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 p-1 ske-float"><div class="skeleton rounded image-reg "></div></div></div> <div class="col-6"><p class="skeleton rounded" style="width:100%; height:20px; "></p><p class="skeleton rounded" style="width:100%; height:20px; "></p><p class="skeleton rounded" style="width:100%; height:20px; "></p><p class="skeleton rounded" style="width:100%; height:20px; "></p><p class="skeleton rounded" style="width:100%; height:20px; "></p><p class="skeleton rounded" style="width:100%; height:20px; "></p></div> </div> </div>',
            right: '<div class="col-12 p-0">  <div class="row">  <div class="col-6"><p class="skeleton rounded" style="width:100%; height:20px; "></p><p class="skeleton rounded" style="width:100%; height:20px; "></p><p class="skeleton rounded" style="width:100%; height:20px; "></p><p class="skeleton rounded" style="width:100%; height:20px; "></p><p class="skeleton rounded" style="width:100%; height:20px; "></p><p class="skeleton rounded" style="width:100%; height:20px; "></p></div>  <div class="col-6"><div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 p-1 ske-float"><div class="skeleton rounded image-reg "></div></div></div> </div> </div>',
        } 
    };
    // NOTE:
    // 	extract user specification, 
    // 	map them to components in the components object,
    //  construct frames and place them/it into target section
    //  as content load indicators 
    for (var i = 0; i < specification.length; i++) {
        // construct user requested amount of skeleton 
        if ( specification[i].includes("*") == true) {
            let splitBuilder      = specification[i].split("*");
            let splitBuilderCount = splitBuilder[1];

            for (var x = 0; x < splitBuilderCount; x++) {
                let newBuilder = 'components.'+splitBuilder[0];
                $(target).append( eval(newBuilder) );
            }
        }
        // construct single skeleton 
        else {
            let newBuilder = 'components.'+specification[i];
            $(target).append( eval(newBuilder) );
        }
    }
}
