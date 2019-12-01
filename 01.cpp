#include <iostream>
#include <string>
#include "input/Input.h"
#include <math.h>

using namespace std;

int calculateFuelRequirementByMass(int mass);

int sumOfFuelRequirements(list<int> massList);

int sumOfFuelRequirementsHavingFuelMass(list<int> massList);

int calculateFuelRequirementOfTheFuel(int requirement);

int main()
{
    Input *input = new Input("01.txt");
    list<int> content = input->getContent();
    int answer1 = sumOfFuelRequirements(content);
    cout << answer1 << endl;
    int answer2 = sumOfFuelRequirementsHavingFuelMass(content);
    cout << answer2 << endl;

}

int sumOfFuelRequirementsHavingFuelMass(list<int> massList) {
    int totalFuelRequirement = 0;
    for(std::list<int>::iterator it = massList.begin(); it != massList.end(); ++it) {
        int fuelMass = calculateFuelRequirementByMass(*it);
        int fuelNeededForTheFuel = calculateFuelRequirementOfTheFuel(fuelMass);
        totalFuelRequirement += fuelMass + fuelNeededForTheFuel;
    }
    return totalFuelRequirement;
}

int calculateFuelRequirementOfTheFuel(int fuelMass) {
    int fuelNeededForTheFuel = calculateFuelRequirementByMass(fuelMass);
    if (fuelNeededForTheFuel > 0) {
        return fuelNeededForTheFuel+calculateFuelRequirementOfTheFuel(fuelNeededForTheFuel);
    }
    return 0;
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