/**
 * Created by felito on 2/24/2016.
 */
chatClient.service('Normalizer',Normalizer);

function Normalizer(){
    return {
        cloneObject:function(object){
            return _cloneObject(object);
        }
    }
}


/**
 * Clones an object.
 * @param obj
 * @returns {{}}
 * @private
 */
function _cloneObject(obj){
    var clone = {};
    for(var i in obj) {
        if(typeof(obj[i])=="object" && obj[i] != null)
            clone[i] = _cloneObject(obj[i]);
        else
            clone[i] = obj[i];
    }
    return clone;
}