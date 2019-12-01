#include <iostream>
#include <string>
#include "input/Input.h"
#include <math.h>

using namespace std;

int calculateFuelRequirementByMass(int mass);

int sumOfFuelRequirements(list<int> massList);

int main()
{
    Input *input = new Input("01.txt");
    list<int> content = input->getContent();
    int answer1 = sumOfFuelRequirements(content);
    cout << answer1 << endl;
}

int sumOfFuelRequirements(list<int> massList) {
    int totalSum = 0;
    for(std::list<int>::iterator it = massList.begin(); it != massList.end(); ++it)
        totalSum += calculateFuelRequirementByMass(*it);
    return totalSum;
}


int calculateFuelRequirementByMass(int mass) {
    return floor(mass/3)-2;
}