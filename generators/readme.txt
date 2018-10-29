//VARIABLE DETERMINATION

//type (string)
Common types
- number
- string
- array
- table
- operator

Geometry types
- point
- glider
- segment
- halfline
- line
- curve
- angle
- triangle
- isoRightAngleTriangle
- isoTriangle
- rightAngleTriangle
- equiTriangle
- quadrilateral
- square
- rectangle
- lozenge
- parallelogram
- circle
- labelGeometry
- valueGeometry

//PARAMETERS
//value (string or number)
Parameter to give when you want the expression to be ultimately evaluated by Math.js. It will return an error if the variable is not evaluable (example : strings not defined by other variables)
If the type of the variable is geometric, the value obtained will be a geometric object (jsx graph).

//expression (string)
Parameter replacing the parameter value if you don't want the expression to be ultimately evaluated (and avoid fatal js error). 
The expression will still be interpreted (i.e. replacing variable names between brackets and using regex to use methods defined in the script).

//visible (boolean)
Parameter to prevent a geometric object to be displayed. Set to true by default.

//fixed (boolean)
Parameter to prevent a geometric object from being dragged by the user. Set to true by default.

//labelDisplay (false, true, "top", "bottom", "left", "right", "center")
Parameter to display a geometric object label (name or other characteristic of the variable) next to the object. Set to false by default.

//labelType ("name", "length", "perimeter", "area", "value", "radius")
Parameter to determine which type of label to associate to a geometric object. Name by default.

//color ("red", "blue", "green", "purple", "yellow", etc.)
Parameter to determine the color of a geometric object. Grey by default.

//radius ("big", "normal")
Just for angle geometric objects. Define the radius of the angled drawn. Normal by default.

//Precision (float)
Define the expected precision for a label (length or perimeter for instance). A precision of 10 means that the number will be rounded to a multiple of 10. A precision of 0.1 means that it will be rounded at 1 decimal. 
1 by default.

//Dash (bool)
Only for segment, halflines and lines. Represent the geometric object with dash instead of a plain line. False by default.

//MATH.JS FUNCTIONS OFTEN USED
randomInt(x, y) => Return a random integer between x (included) and y (excluded)
random(x, y) => Return a random float between x (included) and y (excluded)
round(x, y) => Round x at y decimals
pickRandom([choice1, choice2, choice3]) => pick randomly an element of an array (can be string inside the array)

//GEOMETRY CONSTRUCTORS
In most cases, juste type the type of geometrical object (segment, triangle, square) and then type the letters of the points used in the constructions in the parameter "expression" of the variable.
Ex : segment = AB, triangle = ABC, square = ABCD, etc. 
Angles can be defined either as ABC or d1,d2 where d1 and d2 are 2 intersecting lines.
In some cases you can also use the following constructors :
- lengthSegment(AB) => length of the segment AB
- perimeter(ABCD) => perimeter of the quadrilateral ABCD
- belongsTo(d1) => belongs to the geometrical object mentionned
- parallel(line1,point1) => the line parralel to line1 passing by point1
- perpendicular(line1,point1) => the line perpendicular to line1 passing by point1
- bisector(AB) => the line which is the bisector or the segment AB
- altitude(AB,C) => the line which is the altitude of the segment AB passing by point C
- intersection(object1,object2,num) => the point at the intersection between object1 and object2. The num argument is optionnal and is usefull to determine which intersection point to choose (if there are 2 possible intersections)
- middle(AB) => the point at the middle of AB segment.


//CONDITIONS FOR VARIABLE
Conditions can be mathematical equalities like b != a*10. Here are some other expressions useful for specifying conditions :
- different(array1,array2) => used to specify that an array must be different from the specified arrays
- far(A,B,C) => the geometric object must be far enough from the specified points. Works for points and parallel lines.

//CONDITIONS EVALUATION
For most cases, evaluation is made by math.js using an equality.
To evaluate a segment length use the following form : segmentNameLength
To evaluate an angle value use the following form : angleNameValue
To evaluate a perimeter of a figure, use the following form : figurePerimeter
To evaluate the area of a figure, use the following form : figureArea
In addition, following methods can also be used :
- A belongsTo figure => check whether point A belongs to a geometrical figure (line or circle)
- A inside figure => check whether point A is inside a geometrical figure (circle)
- object1 far(object2,object3,objects4) => check whether object1 is far enough (distance > 2) from other geometrical objects
- barDiagram(serie,name,value) => check whether the value of data "name" of the serie "serie" is equal to "value"

//SHOW ANSWER
Used only for geometrical exercises to show one/the answer.
- placePoint(object,destination) => Place a geometrical point to another place defined by a point.
- lengthSegment(AB,X) => Resize segment AB by moving B to have its length equal to X.
- square(ABCD) => Change C and D position to make ABCD a square.
- rectangle(ABCD) => Change D position to make ABCD a rectangle.
- parallelogram(ABCD) => Change D position to make ABCD a parallelogram.
- lozenge(ABCD) => Change C and D position to make ABCD a lozenge.
- isoRightAngleTriangle(ABC) => Change C position to make ABC an isoRightAngleTriangle.
- isoTriangle(ABC) => Change C position to make ABC an isoTriangle.
- rightAngleTriangle(ABC) => Change C position to make ABC a rightAngleTriangle.
- equiTriangle(ABC) => Change C position to make ABC an equiTriangle.
- bisector(AB,CD) => Change D position to make CD a bisector of the segment AB.
- barDiagram(serie,name,value) => Change the value of data "name" of the serie "serie" to "value"
