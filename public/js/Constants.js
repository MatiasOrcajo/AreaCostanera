// Constants for JS

class ConstantsModel {
    BASE_URL;

    constructor() {
        this.BASE_URL = location.protocol + "//" + location.hostname + (location.port && ":" + location.port) + "/";
    }
}
