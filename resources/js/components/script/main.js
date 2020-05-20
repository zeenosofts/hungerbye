export default {
    validEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        // noinspection JSAnnotator
        return re.test(email);
    },
     async sendPOSTRequest(request,objects){
        //console.log(objects,{objects}); return false;
        let self=this;
        var resp;
        await axios({
            method: 'post',
            url: request,
            params: objects
            }).then(function (response) {
                // handle success
            console.log("then"+response);
                resp= response;

            }).catch(function (response) {
                    // handle error
            Vue.$toast.error(response);
            resp= response.data;
            }).finally(function () {
                    // always executed
            });
        return resp;
    },

}