

const circle = { radius:1,
                Location :{ x:1, y:1}, draw: function(){console.log('draw')} };

circle.draw();

//factory function

function createCircle(radius){
    return{
        radius,
        draw: function(){console.log('draw2');}
    };
}
const circle1 = createCircle(1);
circle1.draw();

//constructor function
function XCircle(radius){
    console.log('this',this);
    this.radius = radius;
    this.draw = function(){console.log('draw3');}
}
const cir2 = new XCircle(2);
cir2.draw();