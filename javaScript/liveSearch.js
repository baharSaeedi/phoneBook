// $.getScript("javaScript/formScript.js")
$(".search").keyup(function (event) {
    var delay = 100;
    event.preventDefault();
    setTimeout(delay);
    if ($(".search").val()=="") {
        $(".searchLive").html("");
        $(".searchBox").addClass("d-none");
        $(".contactsBox").removeClass("d-none");
        $("#moreCon").removeClass("d-none");

    }
    else
    {
        $("#moreCon").addClass("d-none");
        $(".mainBoxCard .contactsBox").addClass("d-none");
        $(".small").html("");

        $.ajax({
            url: "LiveSearching.php",
            type: $("#searchForm").attr("method"),
            data: $("#searchForm").serialize(),

            timeout: 3000,
            beforeSend: function () {
                console.log("Before Sending")
            },
            success: function (response) {
                if (response!="")
                {
                    $(".searchLive").html(response).slideDown();
                }
            },
            error: function () {

                $(".searchLive").html("<p class='lead text-danger text-center'>Error While Ajax Handling!</p>").delay(3000).fadeOut(2000)

            },
            complete: function () {
            }
        })
    }
})