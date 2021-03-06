/// <reference path='../Request/index.ts'/>
/// <reference path='../Modules/Converters/index.ts'/>
/// <reference path='../ResponseInterpreter/index.ts'/>
/// <reference path='../URLGenerator/index.ts'/>

class RequestStation
{
    public static RegisterUser(interpreter : IResponseInterpreter, data : FormData)
    {
        let generator = RequestStation.GetURLGenerator();
        let url       = generator.GetURL("user", "new");
        let request   = new AjaxRequest(interpreter, url, "post", true, data);
        request.Send();
    }

    public static RegisterPetrinet(interpreter : IResponseInterpreter, data : FormData)
    {
        let store = Store.GetInstance();
        let generator = RequestStation.GetURLGenerator();
        let url       = generator.GetURL("petrinet", "new");
        let request   = new AjaxRequest(interpreter, url, "post", true, data);
        request.Send();
    }

    public static GetPetrinet(interpreter : IResponseInterpreter, id : number) {
        let generator = RequestStation.GetURLGenerator();
        let url       = generator.GetURL("petrinet", id.toString());
        let request   = new AjaxRequest(interpreter, url, "GET", true);
        request.Send();
    }

    public static GetPetrinetImage(interpreter : IResponseInterpreter, id : number) {
        let generator = RequestStation.GetURLGenerator();
        let url       = generator.GetURL("petrinet", id.toString(), "image");
	url += "?marking_id=" + Store.GetInstance().GetMarkingId();
        let request   = new AjaxRequest(
	    interpreter, url, "GET", true, undefined, "", "image/svg+xml, */*");
        request.Send();
    }
    
    public static GetFeedback(interpreter : IResponseInterpreter, graph : Graph) {
        let uid = Store.GetInstance().GetUserId();
        let pid = Store.GetInstance().GetPetrinetId();
        let sid = Store.GetInstance().GetSessionId();
	let mid = Store.GetInstance().GetMarkingId();

        let generator = RequestStation.GetURLGenerator();
	let url = generator.GetURL("petrinet", "feedback");
	let data = JSON.stringify({
	    user_id: uid,
	    session_id: sid,
	    graph: graph
	});
        let request = new AjaxRequest(interpreter, url, "POST", true, data, "application/json");
        request.Send();
    }

    public static SetSession(interpreter : IResponseInterpreter, data : FormData) {
        let generator = RequestStation.GetURLGenerator();
	let url = generator.GetURL("session", "new");
        let request = new AjaxRequest(interpreter, url, "POST", true, data);
        request.Send();
    }

    protected static GetURLGenerator()
    {
        let path = Settings.GetInstance().GetApiPath();
        let generator = new URLGenerator(path);
        return generator;
    }
}
