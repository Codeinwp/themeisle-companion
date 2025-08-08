import {
  Button,
  Checkbox,
  createListCollection,
  Field,
  Fieldset,
  Flex,
  HStack,
  Input,
  NumberInput,
  Portal,
  RadioGroup,
  Select,
  Span,
  Switch,
  Text,
} from "@chakra-ui/react";

import { __ } from "@wordpress/i18n";
import { decodeHtml, unregister } from "../utils/common";
import Picker from "./ui/picker";
import { toaster } from "./ui/toaster";

const ModuleControl = ({ setting, tempData, changeOption }) => {
  const selectedValue =
    tempData[setting.id] !== undefined ? tempData[setting.id] : setting.default;

  switch (setting.type) {
  case "checkbox":
    return (
      <Checkbox.Root
        size="sm"
        colorPalette="purple"
        checked={selectedValue === "1"}
        onCheckedChange={({ checked }) => {
          changeOption(setting.id, checked ? "1" : "0");
        }}
      >
        <Checkbox.HiddenInput />
        <Checkbox.Control
          _focus={{
            outlineOffset: "2px",
            outline: "2px solid",
            outlineColor: "purple.500",
          }}
        />
        <Checkbox.Label fontWeight="normal" color="fg.muted">
          {setting.label}
        </Checkbox.Label>
      </Checkbox.Root>
    );
  case "radio":
    return (
      <Fieldset.Root size="sm">
        <Fieldset.Legend>{setting.title}</Fieldset.Legend>
        <RadioGroup.Root
          colorPalette="purple"
          value={parseInt(selectedValue)}
          onValueChange={({ value }) => changeOption(setting.id, value)}
          label={setting.title}
        >
          <HStack gap="4" alignItems="start">
            {setting.options.map((label, value) => (
              <RadioGroup.Item key={value} value={value}>
                <RadioGroup.ItemHiddenInput />
                <RadioGroup.ItemIndicator />
                <RadioGroup.ItemText fontWeight="normal" color="fg.muted">
                  {label}
                </RadioGroup.ItemText>
              </RadioGroup.Item>
            ))}
          </HStack>
        </RadioGroup.Root>
      </Fieldset.Root>
    );
  case "toggle":
    return (
      <Switch.Root
        colorPalette="purple"
        size="sm"
        checked={selectedValue === "1"}
        onCheckedChange={({ checked }) => {
          changeOption(setting.id, checked ? "1" : "0");
        }}
      >
        <Switch.HiddenInput />
        <Switch.Control />
        <Switch.Label fontWeight="normal" color="fg.muted">
          {setting.icon && (
            <HStack gap="2">
              <Span fontSize="sm" dangerouslySetInnerHTML={{ __html: setting.icon }} />
              {setting.label}
            </HStack>
          )}
          {!setting.icon && setting.label}
        </Switch.Label>
      </Switch.Root>
    );
  case "select":
    const selectValues = createListCollection({
      items: Object.entries(setting.options).map(([value, label]) => {
        return { label, value };
      }),
    });

    return (
      <Select.Root
        colorPalette="purple"
        size="sm"
        fontSize="sm"
        collection={selectValues}
        value={[selectedValue.toString()]}
        onValueChange={(event) => {
          changeOption(setting.id, parseInt(event.value[0]));
        }}
      >
        <Select.HiddenSelect />
        <Select.Label>{setting.title}</Select.Label>
        <Select.Control>
          <Select.Trigger>
            <Select.ValueText placeholder={setting.title} />
          </Select.Trigger>
          <Select.IndicatorGroup>
            <Select.Indicator />
          </Select.IndicatorGroup>
        </Select.Control>
        <Portal>
          <Select.Positioner>
            <Select.Content>
              {selectValues.items.map((selectOption) => (
                <Select.Item item={selectOption} key={selectOption.value}>
                  {selectOption.label}
                  <Select.ItemIndicator />
                </Select.Item>
              ))}
            </Select.Content>
          </Select.Positioner>
        </Portal>
      </Select.Root>
    );
  case "text":
    return (
      <Field.Root colorPalette="purple">
        <Field.Label>{setting.title}</Field.Label>
        <Input
          size="sm"
          value={decodeHtml(selectedValue)}
          onChange={(newValue) =>
            changeOption(setting.id, newValue.target.value)
          }
        />
      </Field.Root>
    );
  case "link":
    const isUnregister = setting.id === "analytics_accounts_unregister";
    return (
      <div className="select-wrap">
        <Button
          colorPalette="purple"
          size="sm"
          href={setting.url ? setting.url : null}
          onClick={
            isUnregister
              ? () => {
                unregister(setting.unregisterURL);
              }
              : () => {
                toaster.error({
                  title: __(
                    "The analytics module is not available anymore.",
                    "themeisle-companion"
                  ),
                });
              }
          }
        >
          <div dangerouslySetInnerHTML={{ __html: setting.text }} />
        </Button>
      </div>
    );
  case "color":
    return (
      <Fieldset.Root size="sm" justifyContent="start" alignItems="start">
        <Fieldset.Legend>{setting.label}</Fieldset.Legend>
        <Picker 
          value={selectedValue}
          handleChange={
            ({valueAsString}) => { 
              changeOption(setting.id, valueAsString)
            }
          }
        />
      </Fieldset.Root>
    );

  case "number":
    return (
      <Fieldset.Root size="sm">
        <Fieldset.Legend>{setting.label}</Fieldset.Legend>
        <NumberInput.Root
          size="xs"
          value={selectedValue}
          onValueChange={({value}) => changeOption(setting.id, value)}
          max={setting.max || 800}
          min={setting.min || 0}
          w="100px"
        >
          <NumberInput.Control />
          <NumberInput.Input 
            _focus={{
              shadow: '!none',
              borderColor: '!purple.500',
            }}
            rounded="md"/>
        </NumberInput.Root>
      </Fieldset.Root>
    );
  }
};

export default ModuleControl;