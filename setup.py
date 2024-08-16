# -*- coding: utf-8 -*-
from setuptools import setup, find_packages

try:
    long_description = open("README.rst").read()
except IOError:
    long_description = ""

setup(
    name="projects_catalog",
    version="0.1.0",
    description="Manage the software projects",
    license="MIT",
    author="Danilo Silva",
    author_email="contact@danilocgsilva.me",
    packages=find_packages(),
    install_requires=[
        "mysql-connector-python"
    ],
    long_description=long_description,
    classifiers=[
        "Programming Language :: Python",
        "Programming Language :: Python :: 3.11",
    ]
)
