# ValidationKit

This class is used to validation from server side
This validation is easy


# Steps
1.include that class using include function
eg:require_once("Validation.php");
2.create object for that class in constructor
eg:$this->Validation = new Validation;
3.class validater function whith request parameter and rules array
eg:
$Validation = $this->Validation->validater($this->_request,
                        ["userEmail" => 'required|email|exists:users.email',
                         "passWord"  => 'required',
                         "deviceid"  => 'required'
                        ]);
4.If function call return 0 means validation error is there
eg:
if($Validation == 0)
{
 echo "error";
}

#Rules:
1.required - required parameter,must needed one
2.min      - minimum length
3.max      - maximum length
4.email    - email only allowed
5.exists:tablename.fieldname - check value exist in database
