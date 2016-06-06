package main

import (
	"gopkg.in/mgo.v2"
	"gopkg.in/mgo.v2/bson"
	"time"
	"fmt"
	"log"
)

type Person struct  {
	ID bson.ObjectId `bson:"_id,omitempty"`
	Name string
	Phone string
	TimeStamp time.Time

}

func main() {
	start := time.Now()
	db,e := mgo.Dial("localhost")
	err(e)
	defer db.Close()

	var ( /*
         * set true before inserting fresh data.
         * and then set it false again for insert, select, update.
         */
		isDrop = false
	)

	db.SetMode(mgo.Monotonic,true)

	if isDrop {
		e = db.DB("test").DropDatabase()
		err(e)
	}

	c := db.DB("test").C("people")
	i := mgo.Index{
		Key: []string{"_id"},
		Unique: true,
		DropDups: true,
		Background: true,
		Sparse: true,
	}

	e = c.EnsureIndex(i)
	err(e)
	/*
	 * uncomment below methods for insert, select, update for each cycle
	 */

	/*
	 * send no of rows
	 */
	//insert(c,1000000)
	//update(c)

	findAll(c)

	log.Printf("\t%s\n",time.Since(start))
}

func insert(c *mgo.Collection,n int){
	for i:=0;i<n;i++ {
		t:=time.Now()
		e := c.Insert(&Person{Name:"Hemraj", Phone:"9175290756", TimeStamp:t})
		err(e)
	}
}

func findOne(c *mgo.Collection)  {
	r := &Person{}
	e := c.Find(bson.M{"name":"Hemraj"}).Select(bson.M{"phone":0}).One(&r)
	err(e)
	fmt.Println("phone: ",r)
}

func findAll(c *mgo.Collection)  {
	var r []Person
	e := c.Find(bson.M{"name":"Hemraj"}).Sort("-_id").All(&r)
	err(e)
	fmt.Println("Results: ",r)
}

func update(c *mgo.Collection)  {
	cq := bson.M{"name": "Hemraj"}
	change := bson.M{"$set": bson.M{"phone": "10", "timestamp": time.Now()}}
	e := c.Update(cq, change)
	err(e)
}
func err(e error)  {
	if e != nil{
		panic(e)
	}
}
