#include <iostream>
#include "input/Input.h"

using namespace std;

int calculateFuelRequirementByMass(int fuelMass);

int sumOfFuelRequirements(list<int> &massList);

int sumOfFuelRequirementsHavingFuelMass(list<int> &massList);

int calculateFuelRequirementOfTheFuel(int fuelMass);

int main()
{
    Input input("01.txt");
    list<int> content = input.getContent();
    int answer1 = sumOfFuelRequirements(content);
    cout << answer1 << endl;
    int answer2 = sumOfFuelRequirementsHavingFuelMass(content);
    cout << answer2 << endl;

}

int sumOfFuelRequirementsHavingFuelMass(list<int> &massList) {
    int totalFuelRequirement = 0;
    for (int moduleMass:massList) {
        int fuelMass = calculateFuelRequirementByMass(moduleMass);
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

int sumOfFuelRequirements(list<int> &massList) {
    int totalSum = 0;
    for(int moduleMass:massList)
        totalSum += calculateFuelRequirementByMass(moduleMass);
    return totalSum;
}


int calculateFuelRequirementByMass(int fuelMass) {
    int fuelRequirementWithoutAdjust = floor(fuelMass / 3);
    return fuelRequirementWithoutAdjust - 2;
}