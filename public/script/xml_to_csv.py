import csv
import sys
import xml.etree.ElementTree as ET


def xml_to_csv(xml_file, csv_file):
    tree = ET.parse(xml_file)
    root = tree.getroot()

    with open(csv_file, "w", newline="") as csvfile:
        writer = csv.writer(csvfile)

        # Write headers
        headers = []
        for child in root[0]:
            headers.append(child.tag)
        writer.writerow(headers)

        # Write data
        for element in root.findall(".//"):
            row = []
            for child in element:
                row.append(child.text)
            writer.writerow(row)


if __name__ == "__main__":
    if len(sys.argv) < 3:
        print("Usage: python xml_to_csv.py <input_xml_file> <output_csv_file>")
        sys.exit(1)

    input_xml_file = sys.argv[1]
    output_csv_file = sys.argv[2]

    xml_to_csv(input_xml_file, output_csv_file)
