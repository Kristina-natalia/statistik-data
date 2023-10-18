#include <iostream>
#include <vector>
#include <cmath>

double mean(const std::vector<double>& data) {
    double sum = 0.0;
    for (double value : data) {
        sum += value;
    }
    return sum / data.size();
}

std::vector<double> mode(const std::vector<double>& data) {
    // Implementasi perhitungan modus
}

double median(const std::vector<double>& data) {
    // Implementasi perhitungan median
}

double upperBound(const std::vector<double>& data) {
    // Implementasi perhitungan batas atas
}

double lowerBound(const std::vector<double>& data) {
    // Implementasi perhitungan batas bawah
}

int main() {
    // Ambil input dari stdin
    std::vector<double> data;
    double value;
    while (std::cin >> value) {
        data.push_back(value);
    }

    // Hitung statistik
    double meanValue = mean(data);
    // Hitung modus, median, batas atas, dan batas bawah

    // Tampilkan hasil statistik
    std::cout << "Mean: " << meanValue << "\n";
    // Tampilkan hasil modus, median, batas atas, dan batas bawah

    return 0;
}
