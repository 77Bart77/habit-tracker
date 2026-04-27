import React from "react";
import { StyleSheet, TextInput, TextInputProps } from "react-native";
import { theme } from "./theme";

export function AppInput(props: TextInputProps) {
  return (
    <TextInput
      {...props}
      placeholderTextColor={theme.colors.textMuted}
      style={[styles.input, props.style]}
    />
  );
}

const styles = StyleSheet.create({
  input: {
    borderWidth: 1,
    borderColor: theme.colors.border,
    backgroundColor: theme.colors.glass,
    color: theme.colors.text,
    paddingHorizontal: 12,
    paddingVertical: 12,
    borderRadius: theme.radius.md,
    marginBottom: 10,
  },
});