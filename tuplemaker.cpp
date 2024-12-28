#include <string>
#include <iostream>
using namespace std;

void foodlog()
{
    string usernames[] = {"ryan", "ryan1", "ryan2", "ryan3", "ryan76", "ryan65"};
    string foods[] = {"Chicken Breast", "Chicken", "Peanut Butter", "Quinoa", "Almonds", "Egg", "Kale", "Taco","Strawberries", 
    "Strawberry Cup" ,"Sunflower Seeds", "Sweet Potato" };

    for(int i = 0; i < 6; i++)
    {
        for(int j = 0; j < 31; j++)
        {
            for(int k = 0; k < 5; k++)
            {
                cout << "(" +  string("'") + usernames[i] + "', '" + foods[rand() % 12] + "', " + to_string(rand() % 200) + "," + "'2023-12-" 
                + to_string(j+1) + " 10:00:0" + to_string(k) + "')," << endl;
            }
        }
    };
}
void customfoods()
{
     string usernames[] = {"ryan", "ryan1", "ryan2", "ryan3", "ryan76", "ryan65"};
     string nameparts[] = {"Chicken", "Salmon", "Butter", "Egg", "Taco", "Steak", "Seed", "Good", "Bad", "Juicy", "Dry", "Small"};
    for(int i = 0; i < 6; i++)
    {
        
        for(int k = 0; k < 5; k++)
        {
            cout << "(" +  string("'") + usernames[i] + "', '" + nameparts[rand() % 12] + nameparts[rand() % 12] + 
            "', " + to_string(rand() % 200) + "," + to_string(rand()%10) + "),\n";
            
        }
        
    };

}
void meals(string mealnames[60])
{
    string usernames[] = {"ryan", "ryan1", "ryan2", "ryan3", "ryan76", "ryan65"};
    string nameparts[] = {"Chicken", "Salmon", "Butter", "Egg", "Taco", "Steak", "Seed", "Good", "Bad", "Juicy", "Dry", "Small"};

    int idx = 0;
    for(int i = 1; i < 7; i++)
    {
        
        for(int k = 1; k < 11; k++)
        {
            string name = nameparts[rand() % 12] + nameparts[rand() % 12];
            cout << "(" +  string("'") + name + "', '"  + usernames[i-1] +
            "', " + to_string(rand() % 1000) + "),\n";

            mealnames[idx++] = name;

            
        }
        
    };

}
void mealfoods(string mealnames[60])
{
    string foods[] = {"Chicken Breast", "Chicken", "Peanut Butter", "Quinoa", "Almonds", "Egg", "Kale", "Taco","Strawberries", 
    "Strawberry Cup" ,"Sunflower Seeds", "Sweet Potato" };
    string usernames[] = {"ryan", "ryan1", "ryan2", "ryan3", "ryan76", "ryan65"};
    int idx = 0;
    for(int i = 1; i < 7; i++)
    {
        
        for(int k = 1; k < 11; k++)
        {
            string name = mealnames[idx++];

            for(int j = 0; j < 3; j++)
            {
                cout << "(" +  string("'") + name + "', '"  + foods[rand()%12] +
                "', '" + usernames[i-1] + "', " + to_string(rand()%10)+ "),\n";

            }

            
        }
        
    };

}

void customlog()
{
    string foods[33] = {"a"          ,   
 "as"        ,    
 "ChickenDry",     
 "DrySmall"    ,  
 "GoodButter"   , 
 "GoodJuicy"     ,
 "salmfom"       ,
 "TacoTaco"      ,
 "DryGood"       ,
 "EggSteak"      ,
 "GoodButter"    ,
 "JuicyEgg"      ,
 "SeedSteak"     ,
 "BadButter"     ,
 "DrySteak"      ,
 "JuicyBad"      ,
 "SalmonTaco"    ,
 "SmallJuicy"    ,
 "ButterEgg"     ,
 "EggBad"        ,
 "EggButter"     ,
 "EggChicken"    ,
 "JuicyDry"      ,
 "BadTaco"       ,
 "ButterSteak"   ,
 "DryButter"     ,
 "SalmonButter"  ,
 "SmallTaco"     ,
 "ButterChicken" ,
 "ChickenChicken",
 "SmallEgg"      ,
 "SmallSeed"     ,
 "TacoGood" };

    string names[33] = { "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan76"   ,
 "ryan76"   ,
 "ryan76"   ,
 "ryan76"   ,
 "ryan76"   };

    for(int i = 0; i < 33; i++)
    {
        for(int j = 0; j < 5; j++)
        {
            cout << "('" + foods[i] + "', '" + names[i] + "'," + to_string(rand()%200) + ", '2023-12-" + to_string(rand()%31) + " 00:00:0" + to_string(j) + "'),\n"; 
        }
    }
}

void meallog()
{
    string users[] = { "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan"     ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan1"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan2"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan3"    ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan65"   ,
 "ryan76"   ,
 "ryan76"   ,
 "ryan76"   ,
 "ryan76"   ,
 "ryan76"   ,
 "ryan76"   ,
 "ryan76"   ,
 "ryan76"   ,
 "ryan76"   ,
 "ryan76"   };
    string mealnames[] = {"ButterButter"   ,
 "ButterSalmon"   ,
 "ChickenDry"     ,
 "DryGood"        ,
 "EggBad"         ,
 "GoodButter"     ,
 "GoodGood"       ,
 "SteakGood"      ,
 "TacoTaco"       ,
 "ButterSalmon"   ,
 "GoodDry"        ,
 "JuicyBad"       ,
 "JuicyDry"       ,
 "SeedSmall"      ,
 "SeedSteak"      ,
 "SmallJuicy"     ,
 "SteakSeed"      ,
 "TacoButter"     ,
 "TacoSalmon"     ,
 "BadEgg"         ,
 "ButterEgg"      ,
 "ButterSmall"    ,
 "ChickenBad"     ,
 "ChickenChicken" ,
 "ChickenSteak"   ,
 "JuicyDry"       ,
 "JuicyJuicy"     ,
 "JuicySeed"      ,
 "ChickenChicken" ,
 "ChickenJuicy"   ,
 "DryButter"      ,
 "EggSmall"       ,
 "GoodChicken"    ,
 "SalmonBad"      ,
 "SalmonButter"   ,
 "SeedDry"        ,
 "SteakJuicy"     ,
 "TacoSteak"      ,
 "BadBad"         ,
 "ButterSmall"    ,
 "EggButter"      ,
 "EggEgg"         ,
 "EggSalmon"      ,
 "JuicyTaco"      ,
 "SalmonButter"   ,
 "SmallDry"       ,
 "SmallSmall"     ,
 "TacoButter"     ,
 "BadEgg"         ,
 "BadSmall"       ,
 "DrySteak"       ,
 "EggGood"        ,
 "JuicySalmon"    ,
 "JuicySteak"     ,
 "JuicyTaco"      ,
 "SmallEgg"       ,
 "SmallJuicy"     ,
 "TacoChicken"    

}; 

    for(int i = 0; i < 58; i++)
    {
        for(int j = 0; j < 5; j++)
        {
            cout << "('" + mealnames[i] + "', '" + users[i] + "'," + " '2023-12-" + to_string(rand()%31) + " 00:00:0" + to_string(j) + "'),\n"; 
        }
    }

}
int main()
{
    // string mealnames[60];
    // meals(mealnames);
    // cout << endl << endl << endl;
    // mealfoods(mealnames);

    // customlog();
    meallog();





}




  